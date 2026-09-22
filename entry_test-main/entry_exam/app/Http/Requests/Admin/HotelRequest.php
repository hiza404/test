<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $hotelId = $this->input('hotel_id');

        // If user clicked 'Back' from the confirmation screen, bypass further validation
        if ($this->input('action') === 'back') {
            return [
                'hotel_id' => 'required|integer|exists:hotels,hotel_id',
            ];
        }

        $uniqueRule = Rule::unique('hotels', 'hotel_name')->where(function ($query) {
            return $query->where('prefecture_id', $this->input('prefecture_id'));
        });

        if (!empty($hotelId)) {
            $uniqueRule->ignore($hotelId, 'hotel_id');
        }

        $rules = [
            'hotel_name' => [
                'required',
                'string',
                'max:255',
                $uniqueRule,
            ],
            'prefecture_id' => 'required|integer|exists:prefectures,prefecture_id',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'file_path' => 'nullable|string',
        ];

        if (!empty($hotelId)) {
            $rules['hotel_id'] = 'required|integer|exists:hotels,hotel_id';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'hotel_name.required' => 'ホテル名を入力してください。',
            'hotel_name.unique' => 'この都道府県には既に同じ名前のホテルが存在します。',
            'prefecture_id.required' => '都道府県を選択してください。',
            'hotel_image.image' => '画像ファイルを指定してください。',
            'hotel_image.max' => '画像サイズは2MB以下にしてください。',
        ];
    }
}

