<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスの形式で入力してください。',
            'email.unique' => 'このメールアドレスはすでに使用されています。',
            'earningsBase.required' => '基本報酬(円)を半角数字で入力してください。',
            'earningsBase.numeric' => '基本報酬(円)を半角数字で入力してください。',
            'earningsBase.min' => '基本報酬(円)を1円以上で入力してください。',
            'vehicleModelId.required' => '車両を選択してください。',
            'prefectureId.required' => '活動する都道府県を選択してください。',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => ['required','email',Rule::unique('users')->ignore($this->user()->id)],
            'earningsBase' => ['required','numeric','min:1'],
            'vehicleModelId' => 'required',
            'prefectureId' => 'required'
        ];
    }
}
