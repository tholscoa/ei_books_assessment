<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $fillable=[
        'name', 'book_id'
    ];

    protected $hidden = [
        'id', 'book_id', 'created_at', 'updated_at'
    ];
}
