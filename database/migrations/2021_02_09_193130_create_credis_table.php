<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCredisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('payroll_id');
            $table->foreign('payroll_id')->references('id')->on('payrolls');
            $table->unsignedBigInteger('credit_type_id');
            $table->foreign('credit_type_id')->references('id')->on('credit_types');
            $table->unsignedBigInteger('debtor_id')->comment('Titular');
            $table->foreign('debtor_id')->references('id')->on('users');
            $table->unsignedBigInteger('first_co_debtor')->nullable()->comment('Codeudor 1');
            $table->foreign('first_co_debtor')->references('id')->on('users');
            $table->unsignedBigInteger('second_co_debtor')->nullable()->comment('Codeudor 2');
            $table->foreign('second_co_debtor')->references('id')->on('users');
            $table->date('start_date');
            $table->boolean('refinanced')->default(false);
            $table->decimal('capital_value');
            $table->decimal('transport_value')->default(0);
            $table->decimal('other_value')->default(0);
            $table->decimal('interest')->default(0);
            $table->decimal('commission')->default(0);
            $table->integer('fee');
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
        Schema::dropIfExists('credits');
    }
}
