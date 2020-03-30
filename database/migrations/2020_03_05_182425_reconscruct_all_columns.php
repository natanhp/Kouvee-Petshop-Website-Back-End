<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReconscructAllColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('Suppliers', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
        });

        Schema::table('Products', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
        });

        Schema::table('Customers', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
        });

        Schema::table('Pets', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
        });
        
        Schema::table('Services', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
        });

        Schema::table('PetSizes', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
        });

        Schema::table('PetTypes', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
        });

        Schema::table('ProductRestock', function (Blueprint $table) {
            //
            $table->renameColumn('isDeleted', 'isArrived');
            $table->dateTime('deletedAt', 0);
            $table->dropColumn('date');
        });

        Schema::table('ProductTransactionDetail', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
            $table->dateTime('deletedAt', 0);
        });

        Schema::table('ProductTransaction', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted', 'date');
            $table->dateTime('deletedAt', 0);
        });

        Schema::table('ServiceTransactionDetail', function (Blueprint $table) {
            //
            $table->renameColumn('isDeleted', 'isFinished');
            $table->dateTime('deletedAt', 0);
        });

        Schema::table('ServiceTransaction', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
            $table->dateTime('deletedAt', 0);
        });

        Schema::table('ServiceDetails', function (Blueprint $table) {
            //
            $table->dropColumn('isDeleted');
            $table->dateTime('deletedAt', 0);
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
