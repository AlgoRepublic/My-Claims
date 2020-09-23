<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('identity_document_number');
            $table->string('cell_number');
            $table->string('added_by');
            $table->string('beneficiary_identity')->nullable();
            $table->string('policyholder_death_proof')->nullable();
            $table->integer('is_approved')->default(0);
            $table->integer('approved_by')->nullable();
            $table->timestamp('beneficiary_request_date')->nullable();
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
        Schema::dropIfExists('beneficiaries');
    }
}
