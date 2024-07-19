<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class User2 extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users2';

    protected $primaryKey = 'user_id';

    protected $fillable = ['name','resource_type','resource_id'];

    public function resource():MorphTo 
    {
        return $this->morphTo();
    }
}
