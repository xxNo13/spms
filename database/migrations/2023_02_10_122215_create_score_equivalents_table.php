<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_equivalents', function (Blueprint $table) {
            $table->id();
            $table->double('out_from');
            $table->double('out_to');
            $table->double('verysat_from');
            $table->double('verysat_to');
            $table->double('sat_from');
            $table->double('sat_to');
            $table->double('unsat_from');
            $table->double('unsat_to');
            $table->double('poor_from');
            $table->double('poor_to');
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
        Schema::dropIfExists('score_equivalents');
    }
};
