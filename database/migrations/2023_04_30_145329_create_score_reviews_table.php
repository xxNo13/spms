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
        Schema::create('score_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->integer('designated_id')->nullable();
            $table->boolean('designated_status')->default(false);
            $table->integer('prog_chair_id');
            $table->boolean('prog_chair_status')->default(false);
            $table->boolean('hr_status')->default(false);
            $table->boolean('eval_committee_status')->default(false);
            $table->boolean('review_committee_status')->default(false);
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
        Schema::dropIfExists('score_reviews');
    }
};
