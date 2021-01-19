<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('diagnos_id');
            $table->unsignedBigInteger('product_id')->nullable()->default(null);
            $table->unsignedTinyInteger('active')->default(1)->comment('1 - для текущих активных диагнозов, 0 - для неактивных диагнозов. Для истории.');
            $table->date('issue_date');
            $table->date('detach_date')->nullable()->default(null)->comment('Дата отключения диагноза');
            $table->text('comment')->nullable()->default(null)->comment('Комментарий врача к поставленному диагнозу');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients_diagnoses');
    }
}
