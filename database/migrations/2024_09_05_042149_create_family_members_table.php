<?php

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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->date('birthdate')->nullable(); 
            $table->date('deathdate')->nullable(); 
            $table->string('relation')->nullable(); 
            $table->unsignedBigInteger('parent_id')->nullable(); 
            $table->index('parent_id');
            $table->timestamps();
            // Relasi ke diri sendiri untuk hubungan orang tua-anak
            $table->foreign('parent_id')->references('id')->on('family_members')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
