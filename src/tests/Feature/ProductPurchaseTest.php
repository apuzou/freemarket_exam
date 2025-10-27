<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    // 「購入する」ボタンを押下すると購入が完了する
    public function test_can_complete_purchase()
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
        $response->assertViewHas('item');
        $response->assertViewHas('shippingAddress');
    }

    // 購入した商品は商品一覧画面にて「sold」と表示される
    public function test_purchased_item_shows_sold_badge()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 1,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'purchased_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $items = $response->viewData('items');
        $purchasedItem = $items->first();
        $this->assertTrue($purchasedItem->purchases()->exists());
    }

    // 購入した商品は「プロフィール/購入した商品一覧」に追加される
    public function test_purchased_item_added_to_profile_list()
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
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 1,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'purchased_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200);

        $purchasedItems = $response->viewData('purchasedItems');
        $this->assertEquals(1, $purchasedItems->total());

        $purchasedItem = $purchasedItems->first();
        $this->assertEquals($item->id, $purchasedItem->item->id);
    }
}
