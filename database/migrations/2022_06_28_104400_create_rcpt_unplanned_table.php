<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRcptUnplannedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rcpt_unplanned', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('ponbr');
            $table->integer('line');
            $table->string('part');
            $table->string('partdesc')->nullable();
            $table->string('loc')->nullable();
            $table->string('lot')->nullable();
            $table->date('receiptdate');
            $table->decimal('qty_unplanned', 10, 2);
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
        Schema::dropIfExists('rcpt_unplanned');
    }
}
