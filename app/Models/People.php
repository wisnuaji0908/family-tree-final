<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;
    protected $table= 'people';

    protected $fillable = [
        'name',
        'gender',
        'place_birth',
        'birth_date',
    ];

    public function user(){
        return $this->hasOne(User::class, 'people_id', 'id');
    }
}
