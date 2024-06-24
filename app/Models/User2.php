<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class User2 extends Model
{
    use HasFactory;

    protected $table = 'users2';

    protected $primaryKey = 'user_id';

    protected $fillable = ['name','resource_type','resource_id'];

    public function resource():MorphTo 
    {
        return $this->morphTo();
    }
}
