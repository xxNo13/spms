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
        Schema::create('sub_functs', function (Blueprint $table) {
            $table->id();            
            $table->string('sub_funct');
            $table->foreignId('funct_id')->constrained()->onDelete('cascade');
            $table->foreignId('duration_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sub_funct_user', function (Blueprint $table) {
            $table->foreignId('sub_funct_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('sub_functs');
        Schema::dropIfExists('sub_funct_user');
    }
};
