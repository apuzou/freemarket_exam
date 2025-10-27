<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    // 必要な情報が取得できる
    public function test_can_get_user_info()
    {
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);
        Item::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertViewHas('user');
        $response->assertViewHas('soldItems');
        $response->assertViewHas('purchasedItems');
    }
}
