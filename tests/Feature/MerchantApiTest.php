<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class MerchantApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_merchant_can_access_api_endpoint_when_authenticated(): void
    {
        $user = User::factory()->create(['peran' => 'merchant']);
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/merchant/listings');

        // Debugging: If it fails, dump the response content to see what's happening
        if ($response->getStatusCode() !== 200) {
            $response->dump();
        }

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_merchant_api_endpoint(): void
    {
        $response = $this->getJson('/api/merchant/listings');

        $response->assertStatus(401);
    }
}
