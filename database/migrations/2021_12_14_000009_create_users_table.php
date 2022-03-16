<?php
//=========================
//created at 14-12-2021
//=========================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->unsignedBigInteger('supp_id')->index();
            $table->string('email')->unique();
            $table->string('domain', 25);
            $table->unsignedBigInteger('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
            $table->unsignedBigInteger('role_type_id')->index();
            $table->foreign('role_type_id')->references('id')->on('role_types')->onDelete('restrict');
            $table->unsignedBigInteger('department_id')->index();
            $table->tinyInteger('isActive');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->text('session_id')->nullable();
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
        Schema::dropIfExists('users');
    }
}
