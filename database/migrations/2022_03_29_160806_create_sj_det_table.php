<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSjDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sj_det', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sj_mstr_id')->index();
            $table->foreign('sj_mstr_id')->references('id')->on('sj_mstr')->onDelete('restrict');
            $table->integer('sj_line');
            $table->string('sj_part');
            $table->string('sj_part_desc',255)->nullable();
            $table->string('sj_loc')->nullable();
            $table->decimal('sj_qty_ord',15,2);
            $table->decimal('sj_qty_ship',15,2);
            $table->decimal('sj_qty_input',15,2);
            $table->decimal('sj_qty_rcvd',15,2)->nullable();
            $table->decimal('sj_price_ls',15,2);
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
        Schema::dropIfExists('sj_det');
    }
}
