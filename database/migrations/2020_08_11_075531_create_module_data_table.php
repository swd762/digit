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
            $table->unsignedBigInteger('user_id');
//            $table->foreign('pivot_key')->references('id')->on('modules');
            $table->unsignedBigInteger('module_id');
            $table->decimal('temperature', 5, 1);
            $table->integer('bend');
            $table->boolean('is_real')->nullable()->default(null);
            $table->timestamps();
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
    }
}
