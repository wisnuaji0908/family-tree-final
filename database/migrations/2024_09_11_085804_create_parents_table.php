<?php
use App\Models\People;
use App\Models\Parents;
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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(People::class, 'people_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Parents::class, 'parent_id')->constrained()->cascadeOnDelete(); 
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
