<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ProductRestock', function (Blueprint $table) {
            //
            $table->dateTime('deletedAt', 0)->nullable()->change();
        });

        Schema::table('ProductTransactionDetail', function (Blueprint $table) {
            //
            $table->dateTime('deletedAt', 0)->nullable()->change();
        });

        Schema::table('ProductTransaction', function (Blueprint $table) {
            //
            $table->dateTime('deletedAt', 0)->nullable()->change();
        });

        Schema::table('ServiceTransactionDetail', function (Blueprint $table) {
            //
            $table->dateTime('deletedAt', 0)->nullable()->change();
        });

        Schema::table('ServiceTransaction', function (Blueprint $table) {
            //
            $table->dateTime('deletedAt', 0)->nullable()->change();
        });

        Schema::table('ServiceDetails', function (Blueprint $table) {
            //
            $table->dateTime('deletedAt', 0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ProductRestock', function (Blueprint $table) {
            //
        });
    }
}
