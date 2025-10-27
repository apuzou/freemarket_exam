<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

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
}
