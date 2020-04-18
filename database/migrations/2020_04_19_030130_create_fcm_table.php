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
            $table->integer('id', true);
            $table->timestamps();
            $table->string('token', 200);
            $table->integer('employee_id');
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
        try {
            Schema::table('fcm', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
            });
        } catch(Exception $e) {
            echo $e;
        } finally {
            Schema::dropIfExists('fcm');
        } 
    }
}
