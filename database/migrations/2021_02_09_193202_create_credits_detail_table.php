<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_details', function (Blueprint $table) {
            $table->id();
            $table->integer('code_fee');
            $table->decimal('capital_value');
            $table->decimal('capital_balance');
            $table->decimal('value_fee');
            $table->decimal('value_interest');
            $table->date('expired_date');
            $table->unsignedBigInteger('credit_id');
            $table->foreign('credit_id')->references('id')->on('credits');
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
        Schema::dropIfExists('credit_details');
    }
}
