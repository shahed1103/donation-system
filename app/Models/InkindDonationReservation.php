<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InkindDonationReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inkind_donation_id',
        'status_id',
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
