<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parents extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'people_id',
        'parent_id',
        'parent',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'people_id', 'id');
    }

    public function userParent(): BelongsTo {
        return $this->belongsTo(People::class, 'parent_id', 'id');
    }

    public function people(): BelongsTo
    {
        return $this->belongsTo(People::class, 'people_id', 'id');
    }
}