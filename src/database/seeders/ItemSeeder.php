<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'name' => '腕時計',
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => 1, // 良好
                'image_path' => 'images/Armani+Mens+Clock.jpg',
            ],
            [
                'user_id' => 2,
                'name' => 'HDD',
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => 2, // 目立った傷や汚れなし
                'image_path' => 'images/HDD+Hard+Disk.jpg',
            ],
            [
                'user_id' => 3,
                'name' => '玉ねぎ3束',
                'brand' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 3, // やや傷や汚れあり
                'image_path' => 'images/iLoveIMG+d.jpg',
            ],
            [
                'user_id' => 4,
                'name' => '革靴',
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => 4, // 状態が悪い
                'image_path' => 'images/Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'user_id' => 5,
                'name' => 'ノートPC',
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => 1, // 良好
                'image_path' => 'images/Living+Room+Laptop.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'マイク',
                'brand' => null,
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => 2, // 目立った傷や汚れなし
                'image_path' => 'images/Music+Mic+4632231.jpg',
            ],
            [
                'user_id' => 2,
                'name' => 'ショルダーバッグ',
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 3, // やや傷や汚れあり
                'image_path' => 'images/Purse+fashion+pocket.jpg',
            ],
            [
                'user_id' => 3,
                'name' => 'タンブラー',
                'brand' => null,
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => 4, // 状態が悪い
                'image_path' => 'images/Tumbler+souvenir.jpg',
            ],
            [
                'user_id' => 4,
                'name' => 'コーヒーミル',
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => 1, // 良好
                'image_path' => 'images/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'user_id' => 5,
                'name' => 'メイクセット',
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => 2, // 目立った傷や汚れなし
                'image_path' => 'images/外出メイクアップセット.jpg',
            ]
        ];

        foreach ($items as $item) {
            DB::table('items')->insert([
                'user_id' => $item['user_id'],
                'name' => $item['name'],
                'brand' => $item['brand'],
                'description' => $item['description'],
                'price' => $item['price'],
                'condition' => $item['condition'],
                'image_path' => $item['image_path'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}