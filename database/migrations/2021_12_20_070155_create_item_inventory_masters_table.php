<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemInventoryMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_inventory_masters', function (Blueprint $table) {
            $table->id();
            $table->string('iim_item_domain', 8);
            $table->string('iim_item_part', 20);
            $table->string('iim_item_desc', 50);
            $table->string('iim_item_um', 3);
            $table->string('iim_item_prod_line', 8);
            $table->string('iim_item_group', 8);
            $table->string('iim_item_type', 8);
            $table->tinyInteger('iim_item_isRfq');
            $table->string('iim_item_pm', 2)->nullable();
            $table->integer('iim_item_safety_stk')->default(0);
            $table->integer('iim_item_price')->default(0);
            $table->string('iim_item_promo')->nullable();
            $table->string('iim_item_design')->nullable();
            $table->string('iim_item_safety_email')->nullable();
            $table->integer('iim_item_day1')->nullable();
            $table->integer('iim_item_day2')->nullable();
            $table->integer('iim_item_day3')->nullable();
            $table->string('iim_item_day_email1')->nullable();
            $table->string('iim_item_day_email2')->nullable();
            $table->string('iim_item_day_email3')->nullable();
            $table->string('iim_item_acc')->nullable();
            $table->string('iim_item_subacc')->nullable();
            $table->string('iim_item_costcenter')->nullable();
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
        Schema::dropIfExists('item_inventory_masters');
    }
}
