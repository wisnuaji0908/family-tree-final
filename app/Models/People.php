<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class People extends Model
{
    use HasFactory;
    protected $table = 'people';

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'place_birth',
        'birth_date',
        'death_date',
        'photo_profile',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function couples()
    {
        return $this->hasMany(Couple::class, 'people_id');
    }

    public function parents()
    {
        return $this->hasMany(Parents::class, 'people_id');
    }


    // protected static function boot()
    // {
    //     parent::boot();

    //     // Global scope: Selalu tampilkan data milik admin
    //     static::addGlobalScope('include_admin', function ($query) {
    //         $query->orWhere('user_id', 1); // Data milik admin
    //     });
    // }

}
