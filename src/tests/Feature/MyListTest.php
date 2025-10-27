<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    // いいねした商品だけが表示される
    public function test_only_liked_items_displayed()
    {
        $user = User::factory()->create();
        $item1 = Item::factory()->create();
        $item2 = Item::factory()->create();
        $item3 = Item::factory()->create();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item2->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $items = $response->viewData('items');
        $this->assertEquals(2, $items->total());
    }

    // 購入済み商品は「Sold」と表示される
    public function test_purchased_items_show_sold_badge()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $user->purchases()->create([
            'item_id' => $item->id,
            'payment_method' => 1,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'purchased_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);

        $items = $response->viewData('items');
        $likedItem = $items->first();
        $this->assertTrue($likedItem->purchases()->exists());
    }
}
