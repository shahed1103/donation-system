<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
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
}
