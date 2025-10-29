<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
            'postal_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',
            'profile_image.image' => 'プロフィール画像は画像ファイルを選択してください。',
            'profile_image.mimes' => 'プロフィール画像はjpegまたはpng形式で選択してください。',
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.regex' => '郵便番号はハイフンを含め8文字で入力してください（例: 123-4567）。',
            'address.required' => '住所は必須です。',
            'address.max' => '住所は255文字以内で入力してください。',
        ];
    }
}
