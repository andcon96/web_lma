<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhReceiptdateToPoHist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_hist', function (Blueprint $table) {
            //
            $table->date('ph_receiptdate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_hist', function (Blueprint $table) {
            //
            $table->date('ph_receiptdate');
        });
    }
}
