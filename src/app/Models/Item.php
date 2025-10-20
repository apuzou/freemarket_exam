<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'description',
        'price',
        'condition',
        'image_path',
    ];

    // リレーションシップ
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_categories');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * 商品の状態を具体的表現に変換するアクセサー
     */
    public function getConditionTextAttribute()
    {
        $conditions = [
            '1' => '良好',
            '2' => '目立った傷や汚れなし',
            '3' => 'やや傷や汚れあり',
            '4' => '状態が悪い'
        ];
        
        return $conditions[$this->condition] ?? $this->condition;
    }
}