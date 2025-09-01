<?php

namespace App\Services;


use App\Models\InkindDonation;
use Illuminate\Support\Facades\Auth;
use App\Models\InkindDonationPhoto;
use App\Models\InkindDonationReservation;
use App\Models\StatusOfDonation;
use App\Models\DonationType;

use App\Models\Center;

use Storage;


class InkindDonationService
{

   //show all in-kind donations
   public function showAllInkindDonations(): array{
     $InkindDonationsAll = [];

     $allInkindDonations = InkindDonation::where('inkindDonation_acceptence_id' , 2)->get();
        foreach ($allInkindDonations as $allInkindDonation) {
            $photos = [];

            foreach ($allInkindDonation->inkindDonationPhotos as $inkindDonationPhoto) {
                    $photos[] = [
                     'id' => $inkindDonationPhoto->id ,
                     'photo' => url(Storage::url($inkindDonationPhoto->photo)), 
                  ];
            }
               $InkindDonationsAll[] = [
                  'id' =>  $allInkindDonation->id,
                  'donation_type_id' => ['id' => $allInkindDonation->donation_type_id, 'donation_type' => $allInkindDonation->donationType->donation_Type ],
                  'name_of_donation' => $allInkindDonation->name_of_donation,
                  'amount' => $allInkindDonation->amount,
                  'photo' => $photos
               ];        
      }
         $message = 'all inkind donations retrieved successfully';
         return ['Inkind Donations All' => $InkindDonationsAll, 'message' => $message];
   }

   //show details for in-kind donation
   public function showInkindDonationDetails($id): array{
     $inkindDonationDet = [];

        $inkindDonation = InkindDonation::find($id);
        $photos = [];
            foreach ($inkindDonation->inkindDonationPhotos as $inkindDonationPhoto) {
                    $photos[] = [
                     'id' => $inkindDonationPhoto->id ,
                     'photo' => url(Storage::url($inkindDonationPhoto->photo)), 
                  ];
            }
         $reserved_amount = $inkindDonation->reservations->sum('amount');

               $inkindDonationDet[] = [
                  'donation_type_id' => ['id' => $inkindDonation->donation_type_id , 'donation_type' => $inkindDonation->donationType->donation_Type ],
                  'name_of_donation' => $inkindDonation->name_of_donation,
                  'description' => $inkindDonation->description,
                  'status_of_donation_id' => ['id' => $inkindDonation->status_of_donation_id , 'status_of_donation' => $inkindDonation->statusOfDonation->status ],
                  'center_id' => ['id' => $inkindDonation->center_id , 'center_name' => $inkindDonation->center->center_name],
                  'center_location' => $inkindDonation->center->location,
                  'amount' => $inkindDonation->amount,
                  'reserved_amount' => $reserved_amount,
                  'unreserved_amount' => $inkindDonation->amount - $reserved_amount ,
                  'photo' => $photos
               ];        

         $message = 'inkind donation details retrieved successfully';
         return ['Inkind Donation Details' => $inkindDonationDet, 'message' => $message];
   }

    //search for in-kind donation
    public function searchForNearestInkindDonation($location): array{
      $centers = Center::where('location', 'like', '%' . $location . '%')->pluck('id');

      $inlinkindDonations = InkindDonation::whereIn('center_id' , $centers)->where('inkindDonation_acceptence_id' , 2)->get();
      $InkindDonationsAll = [];

      foreach ($inlinkindDonations as $inlinkindDonation) {
            $photos = [];

            foreach ($inlinkindDonation->inkindDonationPhotos as $inkindDonationPhoto) {
                    $photos[] = [
                     'id' => $inkindDonationPhoto->id ,
                     'photo' => url(Storage::url($inkindDonationPhoto->photo)), 
                  ];
            }
               $InkindDonationsAll[] = [
                  'id' =>  $inlinkindDonation->id,
                  'donation_type_id' => ['id' => $inlinkindDonation->donation_type_id, 'donation_type' => $inlinkindDonation->donationType->donation_Type ],
                  'name_of_donation' => $inlinkindDonation->name_of_donation,
                  'amount' => $inlinkindDonation->amount,
                  'photo' => $photos
               ];        
      }
         $message = 'all inkind donations in this location retrieved successfully';
         return ['Inkind Donations All' => $InkindDonationsAll, 'message' => $message];
   }

    //add in-kind donation
    public function addInkindDonation($request): array{
      $user = Auth::user();
      $inlinkindDonation = InkindDonation::create([
         'donation_type_id' => $request->donation_type_id,
         'name_of_donation' => $request->name_of_donation,
         'description' => $request->description,
         'status_of_donation_id' => $request->status_of_donation_id,
         'center_id' => $request->center_id,
         'amount' => $request->amount,
         'owner_id' => $user->id ,
         'inkindDonation_acceptence_id' => 1
      ]);

        $inlinkindDonationDet = [];
        $inlinkindDonationDet ['in-kind donation'] = $inlinkindDonation;

        $photo_paths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('uploads/inkindDonations', 'public');
                $fullPath = url(Storage::url($path));

                $room_photo = InkindDonationPhoto::create([
                    'photo' =>    $path ,
                    'inkind_donation_id' => $inlinkindDonation->id,
                ]);

                $photo_paths [] = $fullPath;
            }
         $inlinkindDonationDet ['photos'] = $photo_paths;

        }
         $message = 'in-kind donation added successfully';
         return ['Inkind Donation' => $inlinkindDonationDet  , 'message' => $message];
      }

    //reserve in-kind donation
    public function reserveInkindDonation($request , $id): array{
      $user = Auth::user();

      $reserveInkinfDonation = InkindDonationReservation::create([
         'user_id' => $user->id,
         'inkind_donation_id' => $id,
         'amount' => $request->amount,
         'status_id' => 1,
      ]);

      $message = 'in-kind donation reserved successfully';
      return ['Inkind Donation' => $reserveInkinfDonation  , 'message' => $message];

   }

    //1 view all centers
    public function getCenter():array{
        $center = Center::all();
        foreach ($center as $cent) {
            $center_name [] = ['id' => $cent->id  , 'center_name' => $cent->center_name];
        }
        $message = 'all centers are retrived successfully';

        return ['center' =>  $center_name , 'message' => $message];
     }

    //2 view all in-kind donations types
    public function getInkindDonationTypes():array{
        $donationType = DonationType::all();
        foreach ($donationType as $donationTy) {
            $donation_type [] = ['id' => $donationTy->id  , 'donation_type' => $donationTy->donation_Type];
        }
        $message = 'all in-kind donations types are retrived successfully';

        return ['donation_type' =>  $donation_type , 'message' => $message];
     }

    //3 view all in-kind donation statues of object
    public function getStatusOfDonation():array{
        $statusOfDonation = StatusOfDonation::all();
        foreach ($statusOfDonation as $StatusOfDonat) {
            $status_Of_donation [] = ['id' => $StatusOfDonat->id  , 'status_Of_donation' => $StatusOfDonat->status];
        }
        $message = 'all status Of donation are retrived successfully';

        return ['status_Of_donation' =>  $status_Of_donation , 'message' => $message];
     }
}