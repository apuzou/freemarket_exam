<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    // ログイン済みのユーザーはコメントを送信できる
    public function test_logged_in_user_can_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", [
            'comment' => 'テストコメント',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
        $response->assertRedirect("/item/{$item->id}");
    }

    // ログイン前のユーザーはコメントを送信できない
    public function test_unauthenticated_user_cannot_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => 'テストコメント',
        ]);

        $response->assertRedirect('/login');
    }

    // コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_validation_required()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors('comment');
    }

    // コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_comment_validation_max_length()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $longComment = str_repeat('a', 256);

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors('comment');
    }
}
