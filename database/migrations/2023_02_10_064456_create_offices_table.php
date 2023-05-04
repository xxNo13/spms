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
        Schema::create('offices', function (Blueprint $table) {            
            $table->increments('id');
            $table->string('office_name');
            $table->string('office_abbr');
            $table->string('building');
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();
        });
        
        Schema::table('offices', function (Blueprint $table) {          
            $table->foreign('parent_id')->references('id')->on('offices')->onDelete('cascade');
        });

        Schema::create('office_user', function (Blueprint $table) {
            $table->unsignedInteger('office_id');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('isHead')->default(false);
        });

        Schema::table('institutes', function (Blueprint $table) {
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offices');
        Schema::dropIfExists('office_user');
    }
};
