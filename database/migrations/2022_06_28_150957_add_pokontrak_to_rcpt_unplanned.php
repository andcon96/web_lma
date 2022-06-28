<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPokontrakToRcptUnplanned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rcpt_unplanned', function (Blueprint $table) {
            //
            $table->string('pokontrak')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rcpt_unplanned', function (Blueprint $table) {
            //
            $table->dropColumn('pokontrak');
        });
    }
}
