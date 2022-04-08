<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHutangCustTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hutang_cust', function (Blueprint $table) {
            $table->id();
            $table->string('hutang_invcnbr');
            $table->string('hutang_custnbr');
            $table->longText('hutang_cust');
            $table->dateTime('hutang_invcdate');
            $table->float('hutang_amt');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hutang_cust');
    }
}
