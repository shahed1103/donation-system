<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function reservations() {
        return $this->hasMany(InkindDonationReservation::class, 'status_id');
    }
}
