<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'レディース'],
            ['name' => 'メンズ'],
            ['name' => 'ベビー・キッズ'],
            ['name' => 'インテリア・住まい・小物'],
            ['name' => '本・音楽・ゲーム'],
            ['name' => 'おもちゃ・ホビー・グッズ'],
            ['name' => 'コスメ・香水・美容'],
            ['name' => '家電・スマホ・カメラ'],
            ['name' => 'スポーツ・レジャー'],
            ['name' => 'ハンドメイド'],
            ['name' => 'チケット'],
            ['name' => '自動車・オートバイ'],
            ['name' => 'その他']
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}