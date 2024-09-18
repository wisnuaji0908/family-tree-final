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
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(People::class, 'people_id')->constrained('people')->cascadeOnDelete();
            $table->foreignId('couple_id')->nullable()->constrained('couple')->cascadeOnDelete(); 
            $table->date('married_date')->nullable();
            $table->date('divorce_date')->nullable();
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
