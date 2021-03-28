<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules');
            $table->integer('temperature')->comment('Хранение в виде умноженном на 10');
            $table->integer('bend');
            $table->integer('is_real')->nullable()->default(null);
            $table->timestamp('created_at');
        });

        Schema::create('module_data_acc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_data_id');
            $table->foreign('module_data_id')->references('id')->on('module_data');
            $table->integer('x');
            $table->integer('y');
            $table->integer('z');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_data');
        Schema::dropIfExists('module_data_acc');
    }
}
