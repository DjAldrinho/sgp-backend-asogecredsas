<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRefinancedDateInCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->date('refinanced_date')->nullable();
            $table->unsignedBigInteger('refinanced_user')->nullable();
            $table->foreign('refinanced_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->removeColumn('refinanced_date');
            $table->dropConstrainedForeignId('refinanced_user');
        });
    }
}
