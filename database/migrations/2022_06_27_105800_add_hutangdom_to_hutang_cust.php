<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHutangdomToHutangCust extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hutang_cust', function (Blueprint $table) {
            //
            $table->string('hutangdom')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hutang_cust', function (Blueprint $table) {
            //
            $table->dropColumn('hutangdom');
        });
    }
}
