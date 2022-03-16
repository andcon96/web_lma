<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemInventoryControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_inventory_controls', function (Blueprint $table) {
            $table->id();
            $table->string('iic_item_part', 18);
            $table->string('iic_item_prod_line', 10);
            $table->string('iic_item_design', 10);
            $table->string('iic_item_promo', 10);
            $table->string('iic_item_type', 10);
            $table->tinyInteger('iic_item_isRfq');
            $table->string('iic_item_group', 10);
            $table->integer('iic_item_safety')->nullable();
            $table->integer('iic_item_days1')->nullable();
            $table->integer('iic_item_days2')->nullable();
            $table->integer('iic_item_days3')->nullable();
            $table->string('iic_item_email1', 100);
            $table->string('iic_item_email2', 100);
            $table->string('iic_item_email3', 100);
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
        Schema::dropIfExists('item_inventory_controls');
    }
}
