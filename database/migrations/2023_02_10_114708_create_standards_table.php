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
        Schema::create('standards', function (Blueprint $table) {
            $table->id();
            $table->string('eff_5')->nullable();
            $table->string('eff_4')->nullable();
            $table->string('eff_3')->nullable();
            $table->string('eff_2')->nullable();
            $table->string('eff_1')->nullable();
            $table->string('qua_5')->nullable();
            $table->string('qua_4')->nullable();
            $table->string('qua_3')->nullable();
            $table->string('qua_2')->nullable();
            $table->string('qua_1')->nullable();
            $table->string('time_5')->nullable();
            $table->string('time_4')->nullable();
            $table->string('time_3')->nullable();
            $table->string('time_2')->nullable();
            $table->string('time_1')->nullable();
            $table->foreignId('target_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('duration_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('standards');
    }
};
