<?php

namespace App\Services;


use App\Models\InkindDonation;
use App\Models\Center;

use Storage;


class InkindDonationService
{

   //show all in-kind donations
   public function showAllInkindDonations(): array{
     $InkindDonationsAll = [];

     $allInkindDonations = InkindDonation::all();
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

               $inkindDonationDet[] = [
                  'donation_type_id' => ['id' => $inkindDonation->donation_type_id , 'donation_type' => $inkindDonation->donationType->donation_Type ],
                  'name_of_donation' => $inkindDonation->name_of_donation,
                  'description' => $inkindDonation->description,
                  'status_of_donation_id' => ['id' => $inkindDonation->status_of_donation_id , 'status_of_donation' => $inkindDonation->statusOfDonation->status ],
                  'center_id' => ['id' => $inkindDonation->center_id , 'center_name' => $inkindDonation->center->center_name],
                  'amount' => $inkindDonation->amount,
                  'photo' => $photos
               ];        

         $message = 'inkind donation details retrieved successfully';
         return ['Inkind Donation Details' => $inkindDonationDet, 'message' => $message];
   }

    //show details for in-kind donation
    public function searchForNearestInkindDonation($location): array{
      $centers = Center::where('location', 'like', '%' . $location . '%')->pluck('id');

      $inlinkindDonations = InkindDonation::whereIn('center_id' , $centers)->get();
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
      $inlinkindDonation = InkindDonation::create([
         'donation_type_id' => $request->donation_type_id,
         'name_of_donation' => $request->name_of_donation,
         'description' => $request->description,
         'status_of_donation_id' => $request->status_of_donation_id,
         'center_id' => $request->center_id,
         'amount' => $request->amount,
      ]);

        $photo_paths = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $photo) {
                $path = $photo->store('uploads/inkindDonations', 'public');
                $fullPath = url(Storage::url($path));

                $room_photo = InkindDonationPhoto::create([
                    'photo' =>    $path ,
                    'inkind_donation_id' => $inlinkindDonation->id,
                ]);

                $photo_paths [] = $fullPath;
            }
        }
         $message = 'in-kind donation added successfully';
         return ['Inkind Donation' => $inlinkindDonation + $photo_paths , 'message' => $message];
      }
   }