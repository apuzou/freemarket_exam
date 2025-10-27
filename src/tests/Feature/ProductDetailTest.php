<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    // 必要な情報が表示される
    public function test_can_display_all_product_info()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['name' => 'カテゴリ1']);
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 10000,
            'condition' => 1,
            'description' => 'テスト説明',
        ]);
        
        $item->categories()->attach($category->id);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertViewHas('item');
        $response->assertViewHas('likeCount');
        $response->assertViewHas('commentCount');
        $response->assertViewHas('isLiked');
    }

    // 複数選択されたカテゴリが表示されているか
    public function test_multiple_categories_displayed()
    {
        $user = User::factory()->create();
        $category1 = Category::factory()->create(['name' => 'カテゴリ1']);
        $category2 = Category::factory()->create(['name' => 'カテゴリ2']);
        $item = Item::factory()->create(['user_id' => $user->id]);
        
        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $item = $response->viewData('item');
        $this->assertEquals(2, $item->categories->count());
    }
}
