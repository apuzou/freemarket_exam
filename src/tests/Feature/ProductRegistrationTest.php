<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Category;

class ProductRegistrationTest extends TestCase
{
    use RefreshDatabase;

    // 商品出品画面にて必要な情報が保存できること
    public function test_can_save_product_information()
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $file = UploadedFile::fake()->create('product.jpg', 100);

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'price' => 10000,
            'condition' => '1',
            'product_image' => $file,
            'categories' => [$category->id],
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'price' => 10000,
            'condition' => 1,
        ]);
        
        $response->assertRedirect('/');
    }
}
