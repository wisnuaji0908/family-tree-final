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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->date('birthdate')->nullable();
            $table->date('deathdate')->nullable(); 
            $table->foreignId('family_member_id')->constrained('family_members')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
