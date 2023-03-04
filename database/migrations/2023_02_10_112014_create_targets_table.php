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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->string('target');            
            $table->boolean('required')->default(false);
            $table->foreignId('output_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('suboutput_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('duration_id')->constrained()->onDelete('cascade');
            $table->integer('added_by')->nullable();
            $table->timestamps();
        });
        
        Schema::create('target_user', function (Blueprint $table) {
            $table->foreignId('target_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('target_output')->nullable();
            $table->string('alloted_budget')->nullable();
            $table->string('responsible')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targets');
        Schema::dropIfExists('target_user');
    }
};
