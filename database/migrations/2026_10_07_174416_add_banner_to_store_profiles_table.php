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
        Schema::table('store_profiles', function (Blueprint $table) {
            $table->string('banner')->nullable()->after('logo');
            $table->string('banner_badge')->nullable()->after('banner');
            $table->string('banner_text')->nullable()->after('banner_badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_profiles', function (Blueprint $table) {
            $table->dropColumn(['banner', 'banner_badge', 'banner_text']);
        });
    }
};
