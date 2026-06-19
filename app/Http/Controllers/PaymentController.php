<?php

namespace App\Http\Controllers;

use App\Enums\ClaimStatus;
use App\Enums\PaymentStatus;
use App\Models\Claim;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createTransaction(Request $request, $claimId)
    {
        $claim = Claim::with(['user.profil', 'listing'])->find($claimId);

        if (! $claim || $claim->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        if ($claim->status === ClaimStatus::BATAL->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan sudah dibatalkan.',
            ], 400);
        }

        if ($claim->status_pembayaran === PaymentStatus::SUDAH_DIBAYAR->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan ini sudah dibayar.',
            ], 400);
        }

        if ($claim->midtrans_snap_token && $claim->midtrans_transaction_status === 'pending') {
            return response()->json([
                'status' => 'success',
                'message' => 'Snap token sudah tersedia.',
                'snap_token' => $claim->midtrans_snap_token,
                'redirect_url' => $claim->midtrans_redirect_url,
                'order_id' => $claim->midtrans_order_id,
            ], 200);
        }

        $request->validate([
            'metode_pembayaran' => 'required|string|in:qris,dana,gopay,ovo,shopeepay,linkaja,transfer_bank,tunai',
        ]);

        $orderId = 'SAVEAT-'.$claim->id.'-'.time();
        $amount = (int) $claim->total_harga;

        $enabledPayments = $this->mapPaymentMethod($request->metode_pembayaran);

        $user = $claim->user;
        $profil = $user->profil ?? null;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $profil ? $profil->nama : ($user->name ?? 'Konsumen'),
                'email' => $user->email ?? '',
                'phone' => $profil ? ($profil->no_hp ?? '') : '',
            ],
            'item_details' => [
                [
                    
                    'id' => (string) $claim->listing_id,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => $claim->listing ? substr($claim->listing->nama.' (x'.$claim->jumlah.')', 0, 50) : 'Makanan Saveat',
                ],
            ],
           
            'enabled_payments' => $enabledPayments,
        ];

        try {
            $snapResponse = Snap::createTransaction($params);

            $claim->update([
                'metode_pembayaran' => $request->metode_pembayaran,
                'midtrans_order_id' => $orderId,
                'midtrans_snap_token' => $snapResponse->token,
                'midtrans_redirect_url' => $snapResponse->redirect_url,
                'midtrans_transaction_status' => 'pending',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dibuat. Lanjutkan pembayaran.',
                'snap_token' => $snapResponse->token,
                'redirect_url' => $snapResponse->redirect_url,
                'order_id' => $orderId,
                'amount' => $amount,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Midtrans createTransaction error: '.$e->getMessage(), [
                'claim_id' => $claimId,
                'params' => $params,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat transaksi pembayaran: '.$e->getMessage(),
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            $transactionStatus = $request->input('transaction_status');
            $fraudStatus = $request->input('fraud_status');
            $paymentType = $request->input('payment_type');
            $transactionId = $request->input('transaction_id');
            $signatureKey = $request->input('signature_key');
            $statusCode = $request->input('status_code');
            $grossAmount = $request->input('gross_amount');

            $serverKey = config('midtrans.server_key');
            $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

            if ($signatureKey !== $expectedSignature) {
                Log::warning('Midtrans webhook: invalid signature detected.', ['order_id' => $orderId]);

                return response()->json(['status' => 'error', 'message' => 'Invalid signature key.'], 403);
            }

            $claim = Claim::where('midtrans_order_id', $orderId)->first();

            if (! $claim) {
                Log::warning('Midtrans webhook: claim record data not found.', ['order_id' => $orderId]);

                return response()->json(['status' => 'error', 'message' => 'Order data matching record not found.'], 404);
            }

            $updateData = [
                'midtrans_transaction_id' => $transactionId,
                'midtrans_payment_type' => $paymentType,
                'midtrans_transaction_status' => $transactionStatus,
                'midtrans_raw_response' => $request->all(),
            ];

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $updateData['status_pembayaran'] = PaymentStatus::SUDAH_DIBAYAR->value;
                    $updateData['waktu_pembayaran'] = now();
                } else {
                    $updateData['status_pembayaran'] = PaymentStatus::GAGAL->value;
                }
            } elseif ($transactionStatus === 'settlement') {
                $updateData['status_pembayaran'] = PaymentStatus::SUDAH_DIBAYAR->value;
                $updateData['waktu_pembayaran'] = now();
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $updateData['status_pembayaran'] = PaymentStatus::GAGAL->value;
            } elseif ($transactionStatus === 'pending') {
                $updateData['status_pembayaran'] = PaymentStatus::BELUM_DIBAYAR->value;
            }

            $sudahLunasSebelumnya = $claim->status_pembayaran === PaymentStatus::SUDAH_DIBAYAR->value;

            $claim->update($updateData);

            if ($updateData['status_pembayaran'] === PaymentStatus::SUDAH_DIBAYAR->value && ! $sudahLunasSebelumnya) {

                NotificationService::klaimBerhasil(
                    $claim->user_id,
                    $claim->id,
                    $claim->listing ? $claim->listing->nama : 'Pesanan',
                    $claim->listing ? Carbon::parse($claim->listing->batas_waktu)->format('H:i, d M Y') : '-'
                );

                $claim->loadMissing('listing.merchant');

                if ($claim->listing && $claim->listing->merchant && $claim->listing->merchant->user_id) {
                    NotificationService::klaimMasuk(
                        $claim->listing->merchant->user_id,
                        $claim->id,
                        $claim->listing->nama
                    );
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Webhook callback verified and processed.'], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans webhook execution error: '.$e->getMessage(), $request->all());

            return response()->json(['status' => 'error', 'message' => 'Internal webhook processing failed.'], 500);
        }
    }

    public function checkStatus(Request $request, $claimId)
    {
        $claim = Claim::find($claimId);

        if (! $claim || $claim->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'claim_id' => $claim->id,
                'kode_klaim' => $claim->kode_klaim,
                'status_pembayaran' => $claim->status_pembayaran,
                'metode_pembayaran' => $claim->metode_pembayaran,
                'midtrans_order_id' => $claim->midtrans_order_id,
                'midtrans_transaction_id' => $claim->midtrans_transaction_id,
                'midtrans_payment_type' => $claim->midtrans_payment_type,
                'midtrans_transaction_status' => $claim->midtrans_transaction_status,
                'snap_token' => $claim->midtrans_snap_token,
                'redirect_url' => $claim->midtrans_redirect_url,
                'waktu_pembayaran' => $claim->waktu_pembayaran,
            ],
        ], 200);
    }

    private function mapPaymentMethod(string $metode): array
    {
        $map = [
            'qris' => ['qris'],
            'gopay' => ['gopay'],
            'dana' => ['dana'],
            'ovo' => ['other_qris'],
            'shopeepay' => ['shopeepay'],
            'linkaja' => ['other_qris'],
            'transfer_bank' => ['bca_va', 'bni_va', 'bri_va', 'mandiri_bill', 'permata_va', 'other_va'],
            'tunai' => ['indomaret', 'alfamart'],
        ];

        return $map[$metode] ?? ['qris', 'gopay', 'dana', 'shopeepay', 'bca_va', 'bni_va', 'bri_va'];
    }
}
