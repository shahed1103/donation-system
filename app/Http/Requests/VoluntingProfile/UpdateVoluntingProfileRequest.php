<?php

namespace App\Http\Requests\VoluntingProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class UpdateVoluntingProfileRequest extends FormRequest
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
        'availability_type_id' => 'nullable|exists:availability_types,id',
        'skills' => 'nullable|string',
        'availability_hours'=> 'nullable|integer',
        'preferred_tasks' => 'nullable|string',
        'academic_major' => 'nullable|string',
        'previous_volunteer_work' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
