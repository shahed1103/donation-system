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
            $table->integer('donation_type_id')->unsigned();
            $table->string('name_of_donation');
            $table->integer('amount');
            $table->text('description');
            $table->integer('status_of_donation_id')->unsigned();
            $table->integer('center_id')->unsigned();
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
