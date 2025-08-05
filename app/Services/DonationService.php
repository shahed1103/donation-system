<?php


namespace App\Services;

use App\Models\User;
use App\Models\IndCompaign;
use App\Models\Classification;
use App\Models\Association;
use App\Models\CampaignStatus;
use App\Models\GiftDonation;
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
      public function donateWithPoints($request , $campaignType , $campaignId){
         $user = Auth::user();

      $dollarAmount = $request->points / 15;

      $user->points -= $request->points;
      $user->points +=5;
      $user->save();

      if ($campaignType === 'individual') {
            $campaign = IndCompaign::find($campaignId);

            $donation  = Donation::create([
            'user_id' => $user->id,
            'campaign_id' => $campaignId,
            'amount' => $dollarAmount
            ]);
         }

         else{
            $campaign = AssociationCampaign::find($campaignId);

            $donation  = DonationAssociationCampaign::create([
            'user_id' => $user->id,
            'association_campaign_id' => $campaignId,
            'amount' => $dollarAmount
            ]);
         }

      $campaign->updateStatus($campaignType);

      $message = 'donation for this campaign are done sucessfully';

      return ['donation' => $donation  , 'message' => $message];

      }

    // donation with wallet money for campaign
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
      $campaign = IndCompaign::find($campaignId);

      $donation = Donation::create([
      'user_id' => $user->id,
      'campaign_id' => $campaignId,
      'amount' => $request->amount
      ]);
   }


   else{
      $campaign = AssociationCampaign::find($campaignId);

      $donation = DonationAssociationCampaign::create([
      'user_id' => $user->id,
      'association_campaign_id' => $campaignId,
      'amount' => $request->amount
      ]);  
      
      if (!empty($request['gift_token'])) {
        $gift = GiftDonation::where('token', $request['gift_token'])->first();
         if ($gift) {
               $gift->update([
                  'donation_id' => $donation->id,
               ]);
            $sms = app(SmsService::class);
            $text = " تم إهداء تبرع لك من قبل أحد المحبين.\n";
            if ($gift->message) {
                $text .= " الرسالة: {$gift->message}\n";
            }

            if ($gift->show_sender_name && !empty($user->name)) {
                  $text .= "المُهدي: {$gift->sender_name}\n";
            }
            $text .= " مع تحياتنا، فريق العمل.";

            $sms->send($gift->recipient_phone, $text);
      }
    }  
   }
      $campaign->updateStatus($campaignType);

      $message = 'donation for this campaign are done sucessfully';

      return ['donation' => $donation , 'message' => $message]; 
   }

    // quick donation with wallet money for campaign
   public function quickDonateWithWallet($request) : array{
      $user = Auth::user();

      // if($user->wallet ?? null){
      //       throw new Exception("create your wallet first", 401);
      // }

      if( !Hash::check($request->wallet_password ,$user->wallet->wallet_password)){
            throw new Exception("the wallet password you entre is incorrect", 401);
      }

      $user->wallet->wallet_value -= $request->amount;
      $user->load('wallet');

      $user->wallet->save();

      $user->points +=5;
      $user->save();

   if ( $request->campaign_type === 'individual') {
      $campaign = IndCompaign::find($request->campaign_id);

      $donation = Donation::create([
      'user_id' => $user->id,
      'campaign_id' => $request->campaign_id,
      'amount' => $request->amount
      ]);
   }

   else{
      $campaign = AssociationCampaign::find($request->campaign_id);

      $donation = DonationAssociationCampaign::create([
      'user_id' => $user->id,
      'association_campaign_id' =>  $request->campaign_id,
      'amount' => $request->amount
      ]);
      }

      $campaign->updateStatus($request->campaign_type);

      $message = 'donation for this campaign are done sucessfully';

      return ['donation' => $donation , 'message' => $message]; 
   }

   public function giftAdonation($request): array{

    $token = uniqid('gift_', true);
    $gift = GiftDonation::create([
        'recipient_name' => $request['recipient_name'],
        'recipient_phone' => $request['recipient_phone'],
        'message' => $request['message'],
        'show_sender_name' => $request['show_sender_name'] ?? false,
        'token' => $token,
    ]);
      $message = 'gift details are done sucessfully';

      return ['gift' => $gift , 'message' => $message];    }
}
