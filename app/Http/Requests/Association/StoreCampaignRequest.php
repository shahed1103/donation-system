<?php

namespace App\Http\Requests\Association;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class StoreCampaignRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'classification_id' => 'required|integer',
            'amount_required' => 'required|integer|min:1',
           // 'campaign_status_id' => 'required|integer',
            'photo' => 'required|image|mimes:jpg,jpeg,png',
            'compaigns_start_time' => 'required|date',
            'compaigns_end_time' => 'required|date|after_or_equal:compaigns_start_time',
            'compaigns_time' => 'required|integer',
            'emergency_level' => 'required|integer|min:1|max:5',


            'tasks' => 'nullable|array',
            'tasks.*.name' => 'required_with:tasks|string|max:255',
            'tasks.*.description' => 'required_with:tasks|string',
            'tasks.*.number_volunter_need' => 'required_with:tasks|integer|min:1',
            'tasks.*.hours' => 'required_with:tasks|integer|min:1',
        ];
    }


    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
