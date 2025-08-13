<?php

namespace App\Services;

class AssociationCompaignsService
{
  public function showAllInkindDonations(): array{
     $InkindDonationsAll = [];

        $allInkindDonations = InkindDonation::all();
        foreach ($allInkindDonations as $allInkindDonation) {
               $InkindDonationsAll[] = [
                  'id' =>  $allInkindDonation->id,
                  'donation_type_id' => $allInkindDonation->donation_type_id,
                  'name_of_donation' => $allInkindDonation->name_of_donation,
                  'amount' => $allInkindDonation->amount,
                  'photo' => $allInkindDonation->inkindDonationPhotos
                  //url(Storage::url($allInkindDonation->photo)),
               ];        
            }
         $message = 'all inkind donations retrieved successfully';
         return ['Inkind Donations All' => $InkindDonationsAll, 'message' => $message,
         ];
    }

  public function showInkindDonationDetails($id): array{
     $inkindDonationDet = [];

        $inkindDonation = InkindDonation::find('id' , $id);
            // if($inkindDonation->donationType->donation_Type == غذائي)
               $inkindDonationDet[] = [
                //   'id' =>  $inkindDonation->id,
                  'donation_type_id' => ['id' => $inkindDonation->donation_type_id , 'donation_type' => $inkindDonation->donationType->donation_Type ],
                  'name_of_donation' => $inkindDonation->name_of_donation,
                  'description' => $inkindDonation->description,
                  'status_of_donation_id' => ['id' => $inkindDonation->status_of_donation_id , 'status_of_donation' => $inkindDonation->statusOfDonation->status ],
                  'center_id' => ['id' => $inkindDonation->center_id , 'center_name' => $inkindDonation->center->center_name],
                  'amount' => $inkindDonation->amount,
                  'photo' => $inkindDonation->inkindDonationPhotos
               ];        

               $message = 'inkind donation details retrieved successfully';
         return ['Inkind Donation Details' => $inkindDonationDet, 'message' => $message,
         ];
    }

}