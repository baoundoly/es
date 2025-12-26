<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voter_info_id');
            $table->string('voter_no')->nullable();
            $table->string('apartment')->nullable();
            $table->string('flat_no')->nullable();
            $table->string('contact')->nullable();
            $table->unsignedBigInteger('result_id')->nullable();
            $table->string('new_address')->nullable();
            $table->text('extra_info')->nullable();
            $table->timestamps();

            $table->foreign('voter_info_id')->references('id')->on('voter_infos')->onDelete('cascade');
            $table->foreign('result_id')->references('id')->on('results')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveys');
    }
}
