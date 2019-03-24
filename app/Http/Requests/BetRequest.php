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
            'stake_amount' => 'required|min:0.3|max:10000',
            'selections' => 'required|array|min:1|max:20',

            'selections.*.id' => 'required|distinct|integer|min:1',
            'selections.*.odds' => 'required|min:1|max:10000',
        ];
    }
}
