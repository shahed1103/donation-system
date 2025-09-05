<?php

namespace App\Http\Requests\Association;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Responses\response;

class AddAssociationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'date_start_working' => 'required|date',
            'date_end_working' => 'required|date|after:date_start_working',
            'owner_name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'photo' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = "you have sent invalid data";

        throw new ValidationException(
            $validator,
            response()->json(Response::Validation([], $message, $validator->errors()), 422)
        );
    }
}
