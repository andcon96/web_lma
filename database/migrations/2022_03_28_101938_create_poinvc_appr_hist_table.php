<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoinvcApprHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poinvc_appr_hist', function (Blueprint $table) {
            $table->id();
            $table->string('ponbr');
            $table->string('invcnbr');
            $table->string('status');
            $table->dateTime('appr_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poinvc_appr_hist');
    }
}
