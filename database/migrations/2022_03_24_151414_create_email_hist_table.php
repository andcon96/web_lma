<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_hist', function (Blueprint $table) {
            $table->id();
            $table->string('eh_ponbr')->nullable();
            $table->string('eh_invcnbr')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->dateTime('eh_approved_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_hist');
    }
}
