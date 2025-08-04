<?php

namespace App\Http\Requests\Donation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\AssociationCampaign;
use App\Models\IndCompaign;
use App\Http\Responses\response;

class QuickWalletDonationForCompaingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function prepareForValidation()
    {
        $emergencyCampaignsResponse = app(\App\Services\MobileHomeService::class)->emergencyCompaings();
        $campaigns = $emergencyCampaignsResponse['emergency compaings'];

        $randomCampaign = collect($campaigns)->random();

        $this->merge([
            'campaign_id' => $randomCampaign['id'],
            'campaign_type' => $randomCampaign['type'],
        ]);
    }
        /**
     * Get the validation rules that apply to the request.
     *
     *
     */
    public function rules()
    {
 
        return [
        'amount' => [
            'required',
            'integer',
            'min:1000',
            function ($attribute, $value, $fail) {
                
                $userWallet = auth()->user()->wallet->wallet_value;
                    $campaignId = $this->input('campaign_id'); 
                    $campaignType = $this->input('campaign_type');  

                if ($campaignType === 'individual') {
                $campaign = IndCompaign::with('donations')->find($campaignId);
                $amount = $campaign->amount_required - $campaign->donations->sum('amount');
                    }

                else{
                $campaign = AssociationCampaign::with('donationAssociationCampaigns')->find($campaignId);
                $amount = $campaign->amount_required - $campaign->donationAssociationCampaigns->sum('amount');
                    }

               
                if ($value > $amount) {
                    return $fail("the campign need only $amount to complete , donate with this amount or less");
                }

                if ($value > $userWallet) {
                    return $fail('The money amount you put exceeds your available money in your wallet');
                }
            },

        ],
        'wallet_password' => 'required',
        'campaign_id' => ['required'],
        'campaign_type' => ['required', 'in:individual,association'],
        

    ];
    }

    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
