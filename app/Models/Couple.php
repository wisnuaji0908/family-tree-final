<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import model User

class Couple extends Model
{
    use HasFactory;

    protected $table = 'couple';

    protected $fillable = [
        'user_id',
        'people_id',
        'couple_id',
        'married_date',
        'divorce_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    public function partner()
    {
        return $this->belongsTo(People::class, 'couple_id');
    }

    
}
