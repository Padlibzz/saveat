<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\ClaimStatus;
use App\Models\Claim;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// Catatan: Jika composer belum di-install, baris Midtrans ini yang memicu error fatal.
// Namun karena kita menggunakan simulasi penuh, baris config constructor di bawah sudah disesuaikan.
use Midtrans\Config;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Membungkus config lama dengan try-catch agar jika SDK Midtrans tidak sengaja terhapus/belum terinstall,
        // aplikasi web kamu tidak akan mengalami White Screen Of Death (Crash Total).
        try {
            if (class_exists('Midtrans\Config')) {
                Config::$serverKey = config('midtrans.server_key');
                Config::$clientKey = config('midtrans.client_key');
                Config::$isProduction = config('midtrans.is_production');
                Config::$isSanitized = config('midtrans.is_sanitized');
                Config::$is3ds = config('midtrans.is_3ds');
            }
        } catch (\Exception $e) {
            Log::info('Midtrans SDK belum terkonfigurasi, berjalan dalam mode simulasi offline.');
        }
    }

    public function createTransaction(Request $request, $claimId)
    {
        try {
            $claim = Claim::with('user')->findOrFail($claimId);

            $params = [
                'transaction_details' => [
                    'order_id' => $claim->kode_klaim,
                    'gross_amount' => (int) $claim->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $claim->user->name,
                    'email' => $claim->user->email,
                ],
                'enabled_payments' => $this->mapPaymentMethod($request->input('metode_pembayaran', 'qris')),
                'callbacks' => [
                    'finish' => url('/claim/success/' . $claim->id),
                    'error' => url('/checkout/' . $claim->listing_id),
                    'pending' => url('/checkout/' . $claim->listing_id),
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $claim->update([
                'midtrans_snap_token' => $snapToken,
                'metode_pembayaran' => $request->input('metode_pembayaran', 'qris'),
            ]);

            return response()->json([
                'status' => 'midtrans',
                'snap_token' => $snapToken
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal membuat transaksi Midtrans: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        // Tetap dipertahankan untuk arsitektur produksi Midtrans asli nanti
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

            $claim = Claim::where('kode_klaim', $orderId)->first();

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
