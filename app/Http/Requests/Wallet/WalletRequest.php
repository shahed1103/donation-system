<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class UserSignupRequest extends FormRequest
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
            'wallet_value' => 'required|integer|min:6',
            'wallet_password' => 'required|confirmed|min:8',
        ];
    }

    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
