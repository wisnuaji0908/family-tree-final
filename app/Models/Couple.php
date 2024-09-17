<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'couple_id',
        'married_date',
        'divorce_date',
    ];

    public function user(){
        return $this->hasMany(User::class, 'user_id', 'id');
    }

    public function parents(){
        return $this->hasOne(Parents::class, 'parent_id', 'id');
    }
}
