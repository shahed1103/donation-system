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
        Schema::create('inkind_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_type_id')->constrained('donation_types')->onDelete('cascade');
            $table->string('name_of_donation');
            $table->integer('amount');
            $table->text('description');
            $table->foreignId('status_of_donation_id')->constrained('status_of_donations')->onDelete('cascade');
            $table->foreignId('center_id')->constrained('centers')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inkind_donations');
    }
};
