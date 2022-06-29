<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToRcptUnplanned extends Migration
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
            $table->enum('status',['Open','Closed'])->default('Open');
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
            $table->dropColumn('status');
        });
    }
}
