<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_hist', function (Blueprint $table) {
            $table->id();
            $table->string('ph_ponbr');
            $table->integer('ph_line');
            $table->string('ph_part');
            $table->decimal('ph_qty_order',15,2);
            $table->decimal('ph_qty_rcvd',15,2);
            $table->decimal('ph_qty_input',15,2);
            $table->string('created_by');
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
        Schema::dropIfExists('po_hist');
    }
}
