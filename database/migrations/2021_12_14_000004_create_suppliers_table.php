<?php
//=========================
//created at 14-12-2021
//=========================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supp_code');
            $table->string('supp_name');
            $table->string('supp_addr');
            $table->tinyInteger('supp_isActive');
            $table->tinyInteger('supp_po_appr');
            $table->string('supp_phone')->nullable();
            $table->integer('supp_idle_days')->nullable();
            $table->string('supp_idle_emails')->nullable();
            $table->string('supp_email_pur')->nullable();
            $table->integer('supp_day_one')->nullable();
            $table->integer('supp_day_two')->nullable();
            $table->integer('supp_day_three')->nullable();
            $table->integer('supp_day_four')->nullable();
            $table->integer('supp_day_five')->nullable();
            $table->string('supp_email_d_one')->nullable();
            $table->string('supp_email_d_two')->nullable();
            $table->string('supp_email_d_three')->nullable();
            $table->string('supp_email_d_four')->nullable();
            $table->string('supp_email_d_five')->nullable();
            $table->tinyInteger('supp_reapprove')->nullable();
            $table->integer('supp_intv')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
