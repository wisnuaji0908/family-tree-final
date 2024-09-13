<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class People extends Model
{
    use HasFactory;
    protected $table= 'people';

    protected $fillable = [
        'name',
        'gender',
        'place_birth',
        'birth_date',
        'death_date',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function people() {
        return $this->hasOne(People::class, 'parent_id', 'id');
    }
}
