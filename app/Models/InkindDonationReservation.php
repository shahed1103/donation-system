<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InkindDonationReservationUpdater;


class InkindDonationReservation extends Model
{
    use HasFactory;
    use InkindDonationReservationUpdater;

    protected $fillable = [
        'user_id',
        'inkind_donation_id',
        'status_id',
        'amount'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function donation() {
        return $this->belongsTo(InkindDonation::class, 'inkind_donation_id');
    }

    public function status() {
        return $this->belongsTo(ReservationStatus::class, 'status_id');
    }
}
