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
        Schema::create('association_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->integer('classification_id')->unsigned();
            $table->integer('amount_required')->unsigned();
            //$table->foreignId('photo_id')->constrained()->onDelete('cascade');
            // $table->integer('association_id')->unsigned();
            $table->integer('campaign_status_id')->unsigned();
            $table->string('photo');
            $table->date('compaigns_start_time');
            $table->date('compaigns_end_time');
            $table->tinyInteger('emergency_level');
            // $table->integer('compaigns_time');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_campaigns');
    }
};
