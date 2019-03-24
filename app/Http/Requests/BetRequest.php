<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BetRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'player_id' => 'required|integer|min:1',
            'stake_amount' => 'required|numeric|min:0.3|max:10000',
            'selections' => 'required|array|min:1|max:20',

            'selections.*.id' => 'required|distinct|integer|min:1',
            'selections.*.odds' => 'required|numeric|min:1|max:10000',
        ];
    }

    public function messages()
    {
        return [
            'player_id.required' => 'Betslip structure mismatch',
            'stake_amount.required' => 'Betslip structure mismatch',
            'selections.required' => 'Betslip structure mismatch',
            'selections.array' => 'Betslip structure mismatch',
            'selections.*.id.required' => 'Betslip structure mismatch',
            'selections.*.odds.required' => 'Betslip structure mismatch',

            'stake_amount.min' => 'Minimum stake amount is :min',
            'stake_amount.max' => 'Maximum stake amount is :max',

            'selections.min' => 'Minimum number of selections is :min',
            'selections.max' => 'Maximum number of selections is :max',

            'selections.*.id.distinct' => 'Duplicate selection found',
            'selections.*.odds.min' => 'Minimum odds are :min',
            'selections.*.odds.max' => 'Maximum odds are :max',
        ];
    }
}

