<?php

namespace App\Http\Requests\InkindDonation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class AddInkindDonationRequest extends FormRequest
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
        'donation_type_id' => 'required|exists:donation_types,id',
        'name_of_donation' => 'required|string',
        'description'=> 'required|string',
        'status_of_donation_id' => 'required|exists:status_of_donations,id',
        'center_id' => 'required|exists:centers,id',
        'amount' => 'required|integer|min:1',
        'photos' => 'required|array',
        'photos.*' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
