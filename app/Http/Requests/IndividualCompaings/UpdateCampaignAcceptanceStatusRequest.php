<?php

namespace App\Http\Requests\IndividualCompaings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class UpdateCampaignAcceptanceStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:Approved,Rejected',
            'rejection_reason' => 'required_if:status,Rejected|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'enter AcceptanceStatus ',
            'status.in' => 'statuse shoule be rejected or approved',
            'rejection_reason.required_if' => 'enter reject cause',
        ];
    }
}
