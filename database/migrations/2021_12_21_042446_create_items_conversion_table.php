<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsConversionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_conversion', function (Blueprint $table) {
            $table->id();
            $table->string('ic_item_code', 24);
            $table->string('ic_um_1', 5);
            $table->string('ic_um_2', 5);
            $table->decimal('ic_qty_item', 10, 2);
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
        Schema::dropIfExists('items_conversion');
    }
}
