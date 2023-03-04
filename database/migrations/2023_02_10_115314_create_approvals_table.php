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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('approve_id')->nullable();
            $table->smallInteger('approve_status')->nullable();
            $table->date('approve_date')->nullable();
            $table->mediumText('approve_message')->nullable();
            $table->string('type');
            $table->string('user_type');
            $table->smallInteger('added_id')->nullable();
            $table->foreignId('duration_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('approval_review', function (Blueprint $table) {
            $table->foreignId('approval_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            $table->smallInteger('review_status')->nullable();
            $table->date('review_date')->nullable();
            $table->mediumText('review_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
    }
};
