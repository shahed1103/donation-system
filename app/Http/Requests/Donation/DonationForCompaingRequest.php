<?php

namespace App\Http\Requests\Donation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class DonationForCompaingRequest extends FormRequest
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
     *
     *
     */
    public function rules()
    {
        return [
        'points' => [
            'required',
            'integer',
            function ($attribute, $value, $fail) {
                $userPoints = auth()->user()->points;

                if ($userPoints < 15) {
                    return $fail('You must have at least 15 points to redeem.');
                }

                if ($value < 15) {
                    return $fail('You must redeem at least 15 points.');
                }

                if ($value > $userPoints) {
                    return $fail('The number of points to redeem exceeds your available points.');
                }
            },
        ],
    ];
    }

    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
