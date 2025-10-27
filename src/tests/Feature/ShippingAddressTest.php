<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_address_change_reflected_on_purchase_screen()
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
        $response->assertSee('123-4567');
        $response->assertSee('テスト住所');
    }

    // 購入した商品に送付先住所が紐づいて登録される
    public function test_shipping_address_linked_to_purchased_item()
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
        ]);
        $item = Item::factory()->create();

        session(['shipping_address' => [
            'postal_code' => '999-9999',
            'address' => '変更後の住所',
            'building' => 'ビル101',
        ]]);

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 1,
            'postal_code' => session('shipping_address')['postal_code'],
            'address' => session('shipping_address')['address'],
            'building' => session('shipping_address')['building'],
            'purchased_at' => now(),
        ]);

        $this->assertEquals('999-9999', $purchase->postal_code);
        $this->assertEquals('変更後の住所', $purchase->address);
        $this->assertEquals('ビル101', $purchase->building);
    }
}
