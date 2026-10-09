<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('owner_name')->nullable();
            $table->unsignedSmallInteger('founded_year')->nullable();
            $table->string('product_focus')->nullable();
            $table->text('advantages')->nullable(); // satu keunggulan per baris
            $table->string('whatsapp', 20);
            $table->text('whatsapp_greeting')->nullable();
            $table->string('instagram')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_profiles');
    }
};
