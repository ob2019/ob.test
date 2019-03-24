<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

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
     * Custom error messages for BetRequest validations
     *
     * @return array
     */
    public function messages(): array
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'player_id' => 'required|integer|min:1',
            'stake_amount' => 'required|numeric|min:0.3|max:10000',
            'selections' => 'required|array|min:1|max:20',

            'selections.*.id' => 'required|distinct|integer|min:1',
            'selections.*.odds' => 'required|numeric|min:1|max:10000',
        ];
    }


    /**
     * Does some extra actions after validation is done
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            //let's pick all errors
            $errors = $validator->errors()->getMessages();

            //if there are no errors - no more validation actions needed
            if (empty($errors)) {
                return;
            }

            //in case if there were some errors - let's change default validation error output to our custom one,
            //described in api specification
            $request = request()->all();

            $globalErrors = [];
            foreach ($errors as $k => $v) {
                if (strpos($k, "selections.") === false) {
                    //if this is global error
                    $globalErrors[] = [
                        'code' => 1,
                        'message' => $v[0],
                    ];
                } else {
                    //if this is selection error
                    $key = substr($k,0,strrpos($k,'.'));

                    Arr::set($request, $key . ".errors", [
                        'code' => 2,
                        'message' => $v['0']
                    ]);
                }
            }

            if (!empty($globalErrors)) {
                $request['errors'] = $globalErrors;
            }

            response()->json($request, Response::HTTP_BAD_REQUEST)->send();
            exit;
        });
    }
}

