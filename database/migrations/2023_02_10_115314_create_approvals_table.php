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
            $table->integer('superior1_id')->nullable();
            $table->smallInteger('superior1_status')->nullable();
            $table->date('superior1_date')->nullable();
            $table->mediumText('superior1_message')->nullable();
            $table->integer('superior2_id')->nullable();
            $table->smallInteger('superior2_status')->nullable();
            $table->date('superior2_date')->nullable();
            $table->mediumText('superior2_message')->nullable();
            $table->string('type');
            $table->string('user_type');
            $table->smallInteger('added_id')->nullable();
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
        Schema::dropIfExists('approvals');
    }
};
