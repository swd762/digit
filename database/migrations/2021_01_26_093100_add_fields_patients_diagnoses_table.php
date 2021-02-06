<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsPatientsDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('patients_diagnoses', function (Blueprint $table) {
            $table->date('product_attach_date')->nullable()->default(null)->comment('Дата выдачи изделия');
            $table->date('product_detach_date')->nullable()->default(null)->comment('Дата дата возврата изделия');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
