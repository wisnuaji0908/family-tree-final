<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diagram_positions', function (Blueprint $table) {
            $table->id();
            $table->string('node_id')->unique(); // Pastikan node_id unik
            $table->float('x_position');
            $table->float('y_position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagram_positions');
    }
};
