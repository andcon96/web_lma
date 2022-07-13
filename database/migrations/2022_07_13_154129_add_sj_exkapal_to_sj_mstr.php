<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSjExkapalToSjMstr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sj_mstr', function (Blueprint $table) {
            //
            $table->string('sj_exkapal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sj_mstr', function (Blueprint $table) {
            //
            $table->dropColumn('sj_exkapal');
        });
    }
}
