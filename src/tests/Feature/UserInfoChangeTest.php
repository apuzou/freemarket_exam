<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Profile;

class UserInfoChangeTest extends TestCase
{
    use RefreshDatabase;

    // 変更項目が初期値として過去設定されていること
    public function test_initial_values_set_from_past_settings()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);
        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト建物',
            'profile_image' => 'profile_images/profile_edit.jpg',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('storage/profile_images/profile_edit.jpg'); // プロフィール画像
        $response->assertSee('テストユーザー'); // ユーザー名
        $response->assertSee('123-4567'); // 郵便番号
        $response->assertSee('テスト住所'); // 住所
        $response->assertSee('テスト建物'); // 建物名
    }
}
