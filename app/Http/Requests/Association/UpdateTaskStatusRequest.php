<?php

namespace App\Http\Requests\Association;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\response;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:قادمة',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'enter AcceptanceStatus ',
            'status.in' => 'statuse shoule be قادمة ',

        ];
    }
}
