<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Purchase;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    // 必要な情報が取得できる
    public function test_can_get_user_info()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);
        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'profile_image' => 'profile_images/userinfo.jpg',
        ]);

        $item1 = Item::factory()->create(['user_id' => $user->id]);
        $item2 = Item::factory()->create(['user_id' => $user->id]);

        $purchasedItem = Item::factory()->create();
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'payment_method' => 1,
            'postal_code' => '123-4567',
            'address' => 'テスト住所',
            'purchased_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);

        $response->assertViewHas('user');
        $response->assertViewHas('soldItems');
        $response->assertViewHas('purchasedItems');

        $response->assertSee('storage/profile_images/userinfo.jpg');
        $response->assertSee('テストユーザー');

        $soldItems = $response->viewData('soldItems');
        $this->assertEquals(2, $soldItems->total());

        $purchasedItems = $response->viewData('purchasedItems');
        $this->assertEquals(1, $purchasedItems->total());
    }
}
