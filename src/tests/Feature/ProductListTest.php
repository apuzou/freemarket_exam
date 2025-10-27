<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    // 全商品を取得できる
    public function test_can_get_all_items()
    {
        $user = User::factory()->create();
        Item::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('items');
        $this->assertEquals(3, $response->viewData('items')->total());
    }

    // 購入済み商品は「Sold」と表示される
    public function test_purchased_items_show_sold_badge()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->purchases()->create([
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

    // 自分が出品した商品は表示されない
    public function test_own_items_not_displayed()
    {
        $user = User::factory()->create();
        Item::factory()->count(2)->create(['user_id' => $user->id]);
        Item::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $items = $response->viewData('items');
        $this->assertEquals(3, $items->total());
    }
}
