<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockitmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockitm', function (Blueprint $table) {
            $table->id();
            // $table->string('item_site');
            $table->string('item_loc');
            $table->string('item_nbr');
            $table->string('item_desc');
            $table->string('item_um');
            $table->float('item_qtyoh');
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
        Schema::dropIfExists('stockitm');
    }
}
