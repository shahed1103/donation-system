<?php

namespace App\Http\Requests\VoluntingProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class CreateVoluntingProfileRequest extends FormRequest
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
        // 'user_id' => 'required|exists:users,id',
        'availability_type_id' => 'required|exists:availability_types,id',
        'skills' => 'required|string',
        'availability_hours'=> 'required|integer',
        'preferred_tasks' => 'required|string',
        'academic_major' => 'required|string',
        'previous_volunteer_work' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
