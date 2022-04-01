<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratJalanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sj_mstr', function (Blueprint $table) {
            $table->id();
            $table->string('sj_nbr',15);
            $table->string('sj_so_nbr',15);
            $table->string('sj_so_cust',15);
            $table->string('sj_so_ship',15);
            $table->string('sj_so_bill',15);
            $table->date('sj_eff_date');
            $table->string('sj_remark',255);
            $table->enum('sj_status',['New','Closed','Cancelled']);
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
        Schema::dropIfExists('sj_mstr');
    }
}
