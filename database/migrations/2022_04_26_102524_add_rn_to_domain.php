<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRnToDomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('domain_sj_prefix',2)->default('SJ');
            $table->string('domain_sj_rn',8)->default('000000');
            $table->string('domain_rcv_prefix',2)->default('RC');
            $table->string('domain_rcv_rn',8)->default('000000');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('domain_sj_prefix');
            $table->dropColumn('domain_sj_rn');
            $table->dropColumn('domain_rcv_prefix');
            $table->dropColumn('domain_rcv_rn');
        });
    }
}
