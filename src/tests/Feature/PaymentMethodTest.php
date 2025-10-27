<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    // 小計画面で変更が反映される
    public function test_payment_method_change_reflected_in_subtotal()
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('クレジットカード支払い');
        $response->assertSee('コンビニ支払い');

        $response->assertSee('selected-payment-method');
    }
}
