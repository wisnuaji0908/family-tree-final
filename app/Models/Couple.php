<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    use HasFactory;

    // Menyatakan bahwa model ini menggunakan tabel 'couple'
    protected $table = 'couple';

    // Mengisi kolom yang boleh di-mass assign
    protected $fillable = [
        'user_id',
        'people_id',
        'couple_id',
        'married_date',
        'divorce_date',
    ];

    // Relasi ke tabel People untuk orang yang terdaftar
    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    // Relasi ke tabel People untuk pasangan orang yang terdaftar
    public function partner()
    {
        return $this->belongsTo(People::class, 'couple_id');
    }
    
    // Menyatakan bahwa pasangan bisa juga tidak ada (nullable)
    public function couple()
    {
        return $this->belongsTo(People::class, 'couple_id')->withDefault();
    }
}
