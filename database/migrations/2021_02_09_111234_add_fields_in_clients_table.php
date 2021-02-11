<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('position')->nullable();
            $table->integer('salary')->nullable();
            $table->date('start_date')->nullable();
            $table->string('bonding')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->removeColumn('position');
            $table->removeColumn('salary');
            $table->removeColumn('start_date');
            $table->removeColumn('bonding');
        });
    }
}
