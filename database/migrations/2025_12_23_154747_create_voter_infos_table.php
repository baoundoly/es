<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoterInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voter_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('serial_no')->nullable();
            $table->string('name');
            $table->string('voter_no')->unique();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('profession')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->text('address')->nullable();

            $table->unsignedBigInteger('ward_no_id');
            $table->string('file_no');
            $table->string('gender');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('source_file')->nullable();
            $table->tinyInteger('status')->default(1);

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
        Schema::dropIfExists('voter_infos');
    }
}
