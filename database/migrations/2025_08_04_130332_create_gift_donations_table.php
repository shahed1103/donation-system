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
        Schema::create('gift_donations', function (Blueprint $table) {
            $table->id();
            $table->integer('donation_id')->unsigned()->nullable();
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('message');
            $table->string('token')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_donations');
    }
};
