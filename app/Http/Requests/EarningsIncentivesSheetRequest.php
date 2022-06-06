<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EarningsIncentivesSheetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \true;
    }

    public function messages()
    {
        return [
            'title.required' => 'タイトルを入力してください',
            'earnings_incentives.required' => 'インセンティブを入力してください',
            'earnings_incentives.array' => 'array型で入力してください',
            'earnings_incentives.size' => 'インセンティブの数が足りません',
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
            'title' => 'required',
            'earnings_incentives' => 'required|array|size:17',
        ];
    }
}
