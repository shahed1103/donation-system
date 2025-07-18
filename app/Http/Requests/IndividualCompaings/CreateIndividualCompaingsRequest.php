<?php

namespace App\Http\Requests\IndividualCompaings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class CreateIndividualCompaingsRequest extends FormRequest
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
            'title' => 'required|string|min:5',
            'description' => 'required|string|min:10',
            'classification_id' => 'required|exists:classifications,id',
            'location' => 'required|string|min:10',
            'amount_required' => 'required|integer',
            'compaigns_time' =>  'required|integer',
        //    'user_id' => 'required|exists:users,id',
        //    'acceptance_status_id' => 'required|exists:acceptance_statuses,id',
          //  'campaign_status_id' => 'required|exists:campaign_statuses,id',
        ];
    }

    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
