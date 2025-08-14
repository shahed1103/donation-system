<?php

namespace App\Traits;
use Illuminate\Support\Carbon;

trait InkindDonationReservationUpdater
{
    public function updateinkindDonationsReservation(){
        if($this->status_id === 1 && $this->created_at->diffInHours(Carbon::now()) >= 24){
            $this->delete();
        }
    }

}