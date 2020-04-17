<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFcmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fcm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('token', 200);
            $table->integer('employee_id', 11)->unsigned();
            $table->foreign('employee_id')->references('id')->on('Employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropForeign(['employee_id']);
        Schema::dropIfExists('fcm');
    }
}
