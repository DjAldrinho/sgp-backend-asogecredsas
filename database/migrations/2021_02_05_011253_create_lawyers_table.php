<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 50)->default('cc');
            $table->string('document_number', 50)->unique();
            $table->string('name', 150)->comment('Nombre Completo');
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('professional_card')->nullable();
            $table->char('status', 1)->default('A')->comment('A: Activo, I: Inactivo');
            $table->softDeletes();
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
        Schema::dropIfExists('lawyers');
    }
}
