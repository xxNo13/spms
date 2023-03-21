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
        Schema::create('ttmas', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('output');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('head_id');
            $table->foreign('head_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('deadline');
            $table->foreignId('duration_id')->constrained()->onDelete('cascade');
            $table->timestamps();

        });

        Schema::create('ttma_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ttma_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ttmas');
        Schema::dropIfExists('ttma_user');
    }
};
