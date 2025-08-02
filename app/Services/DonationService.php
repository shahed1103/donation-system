<?php


namespace App\Services;

use App\Models\User;
use App\Models\IndCompaign;
use App\Models\Classification;
use App\Models\Association;
use App\Models\CampaignStatus;
use App\Models\DonationAssociationCampaign;
use App\Models\Donation;
use App\Models\SharedAssociationCampaign;
use App\Models\AssociationCampaign;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;
use Carbon\Carbon;

class DonationService
{
      // donation with points for campaign
      public function donateWithPoints($request , $campaignType, $campaignId){
         $user = Auth::user();

      $dollarAmount = $request->points / 15;

      $user->points -= $request->points;
      $user->points +=5;
      $user->save();

      if ($campaignType === 'individual') {
            $donation  = Donation::create([
            'user_id' => $user->id,
            'campaign_id' => $campaignId,
            'amount' => $dollarAmount
            ]);
         }

         else{
            $donation  = DonationAssociationCampaign::create([
            'user_id' => $user->id,
            'association_campaign_id' => $campaignId,
            'amount' => $dollarAmount
            ]);
         }

      $message = 'donation for this campaign are done sucessfully';

      return ['donation' => $donation  , 'message' => $message];

      }

    // donation with wallet money for association campaign
    public function donateWithWallet($request , $campaignType, $campaignId): array {
      $user = Auth::user();

      if( !Hash::check($request->wallet_password ,$user->wallet->wallet_password)){
            throw new Exception("the wallet password you entre is incorrect", 401);
      }

      $user->wallet->wallet_value -= $request->amount;
      $user->load('wallet');

      $user->wallet->save();

      $user->points +=5;
      $user->save();


      if ($campaignType === 'individual') {
      $donation = Donation::create([
      'user_id' => $user->id,
      'campaign_id' => $campaignId,
      'amount' => $request->amount
      ]);
   }


   else{
      $donation = DonationAssociationCampaign::create([
      'user_id' => $user->id,
      'association_campaign_id' => $campaignId,
      'amount' => $request->amount
      ]);   }


      $message = 'donation for this campaign are done sucessfully';

      return ['donation' => $donation , 'message' => $message]; 
   }
}
