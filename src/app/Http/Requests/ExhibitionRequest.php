<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'product_image' => ['required', 'image', 'mimes:jpeg,png'],
            'categories' => ['required', 'array', 'min:1'],
            'condition' => ['required', 'integer', 'between:1,4'],
            'price' => ['required', 'numeric', 'min:0'],
            'brand' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'name.string' => '商品名は文字列で入力してください',
            'name.max' => '商品名は255文字以下で入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.string' => '商品の説明は文字列で入力してください',
            'description.max' => '商品の説明は255文字以下で入力してください',
            'product_image.required' => '商品画像を選択してください',
            'product_image.image' => '商品画像は画像ファイルを選択してください',
            'product_image.mimes' => '商品画像はJPEGまたはPNG形式を選択してください',
            'categories.required' => 'カテゴリーを選択してください',
            'categories.array' => 'カテゴリーは配列で選択してください',
            'categories.min' => 'カテゴリーを1つ以上選択してください',
            'condition.required' => '商品の状態を選択してください',
            'condition.integer' => '商品の状態は整数で選択してください',
            'condition.between' => '商品の状態は1〜4の範囲で選択してください',
            'price.required' => '販売価格を入力してください',
            'price.numeric' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
            'brand.string' => 'ブランド名は文字列で入力してください',
            'brand.max' => 'ブランド名は255文字以下で入力してください',
        ];
    }
}
