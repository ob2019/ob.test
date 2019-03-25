<?php

namespace App\Http\Requests;

use App\Helpers\CustomErrors;
use Illuminate\Http\Exceptions\HttpResponseException;
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
        $betslipError = CustomErrors::getForValidator(1);

        return [
            'player_id.required' => $betslipError,
            'stake_amount.required' => $betslipError,
            'selections.required' => $betslipError,
            'selections.array' => $betslipError,
            'selections.*.id.required' => $betslipError,
            'selections.*.odds.required' => $betslipError,

            'stake_amount.min' => CustomErrors::getForValidator(2),
            'stake_amount.max' => CustomErrors::getForValidator(3),

            'selections.min' => CustomErrors::getForValidator(4),
            'selections.max' => CustomErrors::getForValidator(5),

            'selections.*.odds.min' => CustomErrors::getForValidator(6),
            'selections.*.odds.max' => CustomErrors::getForValidator(7),

            'selections.*.id.distinct' => CustomErrors::getForValidator(8),

            'player_id.max_win_amount' => CustomErrors::getForValidator(9),
            'player_id.is_not_locked' => CustomErrors::getForValidator(10),
            'player_id.sufficient_balance' => CustomErrors::getForValidator(11),
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
            'player_id' => 'required|integer|min:1|sufficient_balance|max_win_amount:20000|is_not_locked',
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
            // let's pick all errors
            $errors = $validator->errors()->getMessages();

            // if there are no errors - no more validation actions needed
            if (empty($errors)) {
                return;
            }

            // in case if there were some errors - let's change default validation error output to our custom one,
            // described in api specification
            $request = request()->all();

            $this->setGlobalErrors($request, $errors);
            $this->setSelectionErrors($request, $errors);

            throw new HttpResponseException(response()->json($request, Response::HTTP_BAD_REQUEST));
        });
    }

    private function setGlobalErrors(array &$request, array $errors): void
    {
        $globalErrors = [];

        foreach ($errors as $k => $v) {
            if (strpos($k, "selections.") === false) {
                // if this is global error

                $globalErrors[] = CustomErrors::getErrorPayload($v[0]);
            }
        }

        if (!empty($globalErrors)) {
            // not sure if keys order is important, but let's make it to be up to date with specification sample
            $selections = $request['selections'];
            unset($request['selections']);

            // add an array with global errors
            $request['errors'] = $globalErrors;

            // place selections block right after global errors array
            $request['selections'] = $selections;
        }
    }

    private function setSelectionErrors(array &$request, array $errors): void
    {
        foreach ($errors as $k => $v) {
            if (strpos($k, "selections.") !== false) {
                // if this is selection error

                // getting path to root of given selection array
                $key = substr($k,0,strrpos($k,'.'));

                Arr::set($request, $key . ".errors", CustomErrors::getErrorPayload($v[0]));
            }
        }
    }
}

