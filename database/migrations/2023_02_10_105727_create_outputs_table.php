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
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('output');
            $table->string('type');
            $table->string('user_type');
            $table->foreignId('sub_funct_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('funct_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('duration_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('output_user', function (Blueprint $table) {
            $table->foreignId('output_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outputs');
        Schema::dropIfExists('output_user');
    }
};
