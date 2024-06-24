<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookShelf extends Model
{
    use HasFactory;

    protected $table = 'book_shelf';

    public $timestamps = false;


    protected $fillable = [
        'shelf_id',
        'book_id'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class, 'shelf_id');
    }

}
