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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('accomplishment');
            $table->integer('efficiency')->nullable();
            $table->integer('quality')->nullable();
            $table->integer('timeliness')->nullable();
            $table->integer('average');
            $table->string('remarks');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('target_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('ratings');
    }
};
