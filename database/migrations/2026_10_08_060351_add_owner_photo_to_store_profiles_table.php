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
            $table->string('owner_photo')->nullable()->after('owner_name');
            $table->string('owner_role')->nullable()->after('owner_photo');
            $table->text('owner_bio')->nullable()->after('owner_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_profiles', function (Blueprint $table) {
            $table->dropColumn(['owner_photo', 'owner_role', 'owner_bio']);
        });
    }
};
