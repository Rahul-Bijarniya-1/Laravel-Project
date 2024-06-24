<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $dates = ['deleted_at'];

    public function shelf()
    {
        return $this->hasMany(Shelf::class);
    }

}
