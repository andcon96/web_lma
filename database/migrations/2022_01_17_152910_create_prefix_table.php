<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefix', function (Blueprint $table) {
            $table->id();
            $table->string('rfq_prefix', 10);
            $table->string('po_prefix', 20);
            $table->string('pr_prefix', 20);
            $table->string('rfp_prefix', 20);
            $table->string('rfq_nbr', 20);
            $table->string('po_nbr', 10);
            $table->string('pr_nbr', 10);
            $table->string('rfp_nbr', 20);
            $table->tinyInteger('pr');
            $table->tinyInteger('po');
            $table->string('sj_prefix', 2);
            $table->integer('sj_year');
            $table->string('sj_month', 2);
            $table->integer('sj_running_nbr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prefix');
    }
}
