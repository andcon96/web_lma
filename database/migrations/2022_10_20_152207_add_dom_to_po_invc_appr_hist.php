<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDomToPoInvcApprHist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_invc_appr_hist', function (Blueprint $table) {
            //
            $table->string('dom')->after('ponbr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_invc_appr_hist', function (Blueprint $table) {
            //
            $table->dropColumn('dom');
        });
    }
}
