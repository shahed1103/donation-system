<?php

namespace App\Http\Requests\Leader;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class LeaderFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => 'required|exists:ind_compaigns,id',
            'visit_date' => 'required|date',
            'leader_name' => 'required|string|max:255',
            'location_type' => 'required|string|max:255',
            'description' => 'required|string',
            'number_of_beneficiaries' => 'required|integer|min:1',
            'beneficiary_type' => 'required|string|max:255',
            'need_type' => 'required|string|max:255',
            'is_need_real' => 'required|boolean',
            'has_other_support' => 'required|boolean',
            'marks_from_5' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
            'recommendation' => 'required|string|in:نوصي بالقبول,نوصي بالرفض,نوصي بإعادة التقييم',
        ];
    }

    public function messages(): array
    {
        return [
            'campaign_id.required' => 'campaign id is required',
            'campaign_id.exists' => 'campaign not found',

            'visit_date.required' => 'visit date is required',
            'visit_date.date' => 'visit date must be a valid date',

            'leader_name.required' => 'leader name is required',

            'location_type.required' => 'location type is required',
            'description.required' => 'description is required',

            'number_of_beneficiaries.required' => 'number of beneficiaries is required',
            'number_of_beneficiaries.integer' => 'number of beneficiaries must be a number',

            'beneficiary_type.required' => 'beneficiary type is required',
            'need_type.required' => 'need type is required',

            'is_need_real.required' => 'please specify if the need is real',
            'has_other_support.required' => 'please specify if other support exists',

            'marks_from_5.required' => 'you must provide a rating',
            'marks_from_5.min' => 'rating must be at least 1',
            'marks_from_5.max' => 'rating must not exceed 5',

            'recommendation.required' => 'recommendation is required',
            'recommendation.in' => 'recommendation must be one of: نوصي بالقبول، نوصي بالرفض، نوصي بإعادة التقييم',
        ];
    }
}
