<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Customer extends Model
{
    use HasFactory;

    protected $table = "customers";
    protected $fillable = ['phone_number'];

    public function users2(): MorphOne
    {
        return $this->morphOne(User2::class, 'resource');
    }

    public function tripRequests(): HasMany 
    {
        return $this->hasMany(TripRequest::class);
    }
}
