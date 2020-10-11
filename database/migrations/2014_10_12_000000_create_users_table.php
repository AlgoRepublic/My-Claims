<?php

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
            $table->string('surname');
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->string('password');
            $table->string('identity_document_number');
            $table->integer('role_id');
            $table->rememberToken();
            $table->string('reset_password_token', 10);
            $table->timestamp('reset_password_token_date');
            $table->integer('payment_verified')->default(0);
            $table->integer('package_id')->nullable();
            $table->integer('archived')->default(0);
            $table->rememberToken();
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
