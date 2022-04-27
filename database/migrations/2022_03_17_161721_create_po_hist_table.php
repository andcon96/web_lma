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
            $table->decimal('ph_qty_fg',15,2);
            $table->decimal('ph_qty_rjct',15,2);
            $table->longText('ph_nopol')->nullable();
            $table->date('ph_receiptdate');
            $table->string('created_by');
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
        Schema::dropIfExists('po_hist');
    }
}
