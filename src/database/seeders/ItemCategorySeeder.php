<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCategorySeeder extends Seeder
{
    public function run()
    {
        $itemCategories = [
            ['item_id' => 1, 'category_id' => 2], // 腕時計 -> メンズ
            ['item_id' => 2, 'category_id' => 8], // HDD -> 家電・スマホ・カメラ
            ['item_id' => 3, 'category_id' => 13], // 玉ねぎ3束 -> その他
            ['item_id' => 4, 'category_id' => 1], // 革靴 -> レディース
            ['item_id' => 5, 'category_id' => 8], // ノートPC -> 家電・スマホ・カメラ
            ['item_id' => 6, 'category_id' => 8], // マイク -> 家電・スマホ・カメラ
            ['item_id' => 7, 'category_id' => 1], // ショルダーバッグ -> レディース
            ['item_id' => 8, 'category_id' => 4], // タンブラー -> インテリア・住まい・小物
            ['item_id' => 9, 'category_id' => 4], // コーヒーミル -> インテリア・住まい・小物
            ['item_id' => 10, 'category_id' => 7], // メイクセット -> コスメ・香水・美容
        ];

        foreach ($itemCategories as $itemCategory) {
            DB::table('item_categories')->insert([
                'item_id' => $itemCategory['item_id'],
                'category_id' => $itemCategory['category_id'],
                'created_at' => now(),
            ]);
        }
    }
}