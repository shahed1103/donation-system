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
        Schema::create('inkind_donation_reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('inkind_donation_id')->constrained('inkind_donations')->onDelete('cascade');
        $table->foreignId('status_id')->constrained('reservation_statuses')->onDelete('cascade');
        $table->string('amount');
        $table->timestamps();

        $table->unique(['user_id', 'inkind_donation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inkind_donation_reservations');
    }
};
