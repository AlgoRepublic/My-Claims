<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('beneficiary_identity');
            $table->string('policyholder_death_proof');
            $table->integer('is_approved')->default(0)->comment('0=no action,1 = approved ,2 = declined ');
            $table->integer('approved_by')->nullable();
            $table->string('email_preference');
            $table->timestamp('approved_date')->nullable();
            $table->timestamp('beneficiary_request_date');
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
        Schema::dropIfExists('claims');
    }
}
