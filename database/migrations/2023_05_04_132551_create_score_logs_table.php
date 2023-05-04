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
        Schema::create('score_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('old_eff');
            $table->integer('old_qua');
            $table->integer('old_time');
            $table->float('old_ave');
            $table->integer('new_eff');
            $table->integer('new_qua');
            $table->integer('new_time');
            $table->float('new_ave');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('rating_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('score_logs');
    }
};
