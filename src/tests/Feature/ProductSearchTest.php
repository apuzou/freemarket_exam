<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    // 「商品名」で部分一致検索ができる
    public function test_can_search_by_item_name()
    {
        Item::factory()->create(['name' => 'テスト商品1']);
        Item::factory()->create(['name' => 'テスト商品2']);
        Item::factory()->create(['name' => '別の商品']);

        $response = $this->get('/search?keyword=テスト商品');

        $response->assertStatus(200);
        $items = $response->viewData('items');
        $this->assertEquals(2, $items->total());
    }

    // 検索状態がマイリストでも保持されている
    public function test_search_state_preserved_in_mylist()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => 'テスト商品']);

        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/search?keyword=テスト商品&tab=mylist');

        $response->assertStatus(200);
        $items = $response->viewData('items');
        $this->assertEquals(1, $items->total());
    }
}
