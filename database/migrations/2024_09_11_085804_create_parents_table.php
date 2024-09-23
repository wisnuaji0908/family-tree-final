<?php
use App\Models\People;
use App\Models\Parents;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(People::class, 'people_id')->constrained('people')->cascadeOnDelete();
    
    // Relasi parent_id ke tabel people, bukan ke parents
            $table->foreignIdFor(People::class, 'parent_id')->nullable()->constrained('people')->cascadeOnDelete();
            $table->enum('parent', ['father', 'mother']);
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
