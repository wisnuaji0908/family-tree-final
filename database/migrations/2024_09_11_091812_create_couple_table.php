<?php

use App\Models\People;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('couple', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained('users')->cascadeOnDelete();
            
            // Relasi ke tabel people untuk 'people_id'
            $table->foreignIdFor(People::class, 'people_id')->constrained('people')->cascadeOnDelete();
            
            // Relasi ke tabel people untuk 'couple_id' (pasangan), bukan ke tabel 'couple'
            $table->foreignId('couple_id')->nullable()->constrained('people')->cascadeOnDelete();

            // Tanggal menikah dan tanggal cerai
            $table->date('married_date')->nullable();
            $table->date('divorce_date')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couple');
    }
};
