<?php

namespace App\Traits;

trait InkindDonationUpdater
{
    public function updateinkindDonations()
    {
       $amount = $this->amount ;
       $reserved_amount = $this->reservations->sum('amount');
       $unreserved_amount =  $amount -  $reserved_amount;

       if($unreserved_amount === 0 && $this->reservations->where('status_id', '===', '1')->count() === 0){
            $this->inkindDonation_acceptence_id = 3;
            $this->load('statusOfAcceptence');
            $this->save();
       }

        if($this->inkindDonation_acceptence_id === 1 && $this->created_at->diffInDays(now()) >= 7){
            $this->delete() ;
        }



    }
}