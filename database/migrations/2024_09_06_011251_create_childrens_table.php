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
        Schema::create('children', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('family_member_id')->nullable();
            $table->foreign('family_member_id')->references('id')->on('family_members')->onDelete('cascade');
            $table->unsignedBigInteger('father_id')->nullable();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->foreign('father_id')->references('id')->on('family_members')->onDelete('set null');
            $table->foreign('mother_id')->references('id')->on('family_members')->onDelete('set null');   
            $table->string('name')->nullable(); 
            $table->date('birthdate')->nullable();
            $table->string('birthplace')->nullable();
            $table->date('deathdate')->nullable(); 
            $table->string('religion')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced'])->nullable();
            $table->timestamps(); 
        });
          
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childrens');
    }
};
