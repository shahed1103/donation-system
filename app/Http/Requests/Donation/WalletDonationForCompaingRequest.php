<?php

namespace App\Http\Requests\Donation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\AssociationCampaign;
use App\Models\IndCompaign;
use App\Http\Responses\response;

class WalletDonationForCompaingRequest extends FormRequest
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
        'amount' => [
            'required',
            'integer',
            'min:1000',
            function ($attribute, $value, $fail) {
                $userWallet = auth()->user()->wallet->wallet_value;
                $campaignId = $this->route('campaignId'); 
                $campaignType =  $this->route('campaignType'); 

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
        'gift_token' => 'nullable|string|exists:gift_donations,token',    
    ];
    }

    protected function failedValidation(Validator $validator){

        //Throw a ValidationException with the translated error messages
        $message = "you have sent invalid data";

        throw new ValidationException($validator, Response::Validation([], $message , $validator->errors()));
    }
}
