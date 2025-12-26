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
        if (Schema::hasTable('surveys') && Schema::hasTable('voter_infos')) {
            Schema::table('surveys', function (Blueprint $table) {
                if (Schema::hasColumn('surveys', 'voter_info_id')) {
                    try {
                        $table->foreign('voter_info_id')->references('id')->on('voter_infos')->onDelete('cascade');
                    } catch (\Exception $e) {
                        // ignore errors (e.g., constraint already exists or unsupported platform)
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('surveys')) {
            Schema::table('surveys', function (Blueprint $table) {
                try {
                    $table->dropForeign(['voter_info_id']);
                } catch (\Exception $e) {
                    // ignore if foreign key not present
                }
            });
        }
    }
};
