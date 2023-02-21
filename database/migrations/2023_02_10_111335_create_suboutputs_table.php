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
        Schema::create('suboutputs', function (Blueprint $table) {
            $table->id();
            $table->string('suboutput');
            $table->foreignId('output_id')->constrained()->onDelete('cascade');
            $table->foreignId('duration_id')->constrained()->onDelete('cascade');
            $table->integet('added_by')->nullable();
            $table->timestamps();
        });

        
        Schema::create('suboutput_user', function (Blueprint $table) {
            $table->foreignId('suboutput_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('suboutputs');
        Schema::dropIfExists('suboutput_user');
    }
};
