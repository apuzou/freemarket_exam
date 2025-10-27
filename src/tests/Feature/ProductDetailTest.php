<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    // 必要な情報が表示される
    public function test_can_display_all_product_info()
    {
        $seller = User::factory()->create(['name' => '出品者']);
        $commenter = User::factory()->create(['name' => 'コメント投稿者']);
        $category = Category::factory()->create(['name' => 'カテゴリ1']);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 10000,
            'condition' => 1,
            'description' => 'テスト説明',
            'image_path' => 'images/test.jpg',
        ]);

        $item->categories()->attach($category->id);

        // いいねを追加
        Like::create([
            'user_id' => $commenter->id,
            'item_id' => $item->id,
        ]);

        // コメントを追加
        Comment::create([
            'user_id' => $commenter->id,
            'item_id' => $item->id,
            'comment' => 'コメント内容',
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        // ビューに必要なデータが渡されているか確認
        $response->assertViewHas('item');
        $response->assertViewHas('likeCount', 1);
        $response->assertViewHas('commentCount', 1);
        $response->assertViewHas('isLiked');

        // 表示内容を確認
        $response->assertSee('storage/images/test.jpg'); // 商品画像
        $response->assertSee('テスト商品'); // 商品名
        $response->assertSee('テストブランド'); // ブランド名
        $response->assertSee('10,000'); // 価格（フォーマット済み）
        $response->assertSee('1'); // いいね数
        $response->assertSee('1'); // コメント数
        $response->assertSee('テスト説明'); // 商品説明
        $response->assertSee('カテゴリ1'); // カテゴリ名
        $response->assertSee('コメント投稿者'); // コメントユーザー名
        $response->assertSee('コメント内容'); // コメント内容
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
