<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $fillable = ['type'];



    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
