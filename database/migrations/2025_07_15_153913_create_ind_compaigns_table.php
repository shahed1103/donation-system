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
        Schema::create('ind_compaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('classification_id')->unsigned();
            $table->string('location');
            $table->integer('amount_required')->unsigned();
            //$table->foreignId('photo_id')->constrained()->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->integer('acceptance_status_id')->unsigned()->default(1);
            $table->integer('campaign_status_id')->unsigned()->default(2);
          //  $table->dateTime('compaigns_time');
         // 2025-08-01 10:30:00
        //'compaigns_time' => now()->addDays(7),
        
        //$table->date('compaigns_time');
        //2025-08-01
        //'compaigns_time' => now()->addDays(7)->toDateString(),

            $table->integer('compaigns_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ind_compaigns');
    }
};
