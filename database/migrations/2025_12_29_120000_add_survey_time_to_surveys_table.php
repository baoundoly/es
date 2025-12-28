<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSurveyTimeToSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('surveys', 'survey_time')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->integer('survey_time')->nullable()->after('new_address')->comment('survey time as integer (e.g., epoch or minutes)');
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
        Schema::table('surveys', function (Blueprint $table) {
            if (Schema::hasColumn('surveys', 'survey_time')) {
                $table->dropColumn('survey_time');
            }
        });
    }
}
