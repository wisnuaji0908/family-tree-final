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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('japati_token')->after('app_logo')->nullable();
            $table->string('japati_gateway')->after('japati_token')->nullable();
            $table->string('japati_url')->after('japati_gateway')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['japati_token', 'japati_gateway', 'japati_url']);
        });
    }
};
