<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leader_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->date('visit_date');
            $table->string('leader_name');

            $table->string('location_type');  //نوع المكان الذي تمت زيارته
            $table->text('description');

            $table->integer('number_of_beneficiaries');  //عدد المستفيدين
            $table->string('beneficiary_type');   //الفئة المستهدفة
            $table->string('need_type');  //نوع الاحتياج

            $table->boolean('has_other_support'); // هل توجد جهات أخرى داعمة؟

            $table->integer('marks_from_5'); // تقييم الحالة كم هي واقعية من 5
            $table->string('notes')->nullable();

            $table->string('recommendation');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_types');
    }
};
