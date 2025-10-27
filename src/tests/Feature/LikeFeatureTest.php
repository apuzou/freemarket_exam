<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    // いいねアイコンを押下することによって、いいねした商品として登録することができる
    public function test_can_like_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/item/{$item->id}/like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $response->assertRedirect("/item/{$item->id}");
    }

    // 追加済みのアイコンは色が変化する
    public function test_liked_item_shows_different_color()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        $response->assertStatus(200);
        $this->assertTrue($response->viewData('isLiked'));
    }

    // 再度いいねアイコンを押下することによって、いいねを解除することができる
    public function test_can_unlike_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->post("/item/{$item->id}/like");

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $response->assertRedirect("/item/{$item->id}");
    }
}
