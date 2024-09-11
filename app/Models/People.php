<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'couple_id',
        'name',
        'gender',
        'place_birth',
        'birth_date',
    ];
}
