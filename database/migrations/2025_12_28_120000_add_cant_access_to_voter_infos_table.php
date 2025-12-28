<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCantAccessToVoterInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voter_infos', function (Blueprint $table) {
            $table->tinyInteger('cant_access')->nullable()->after('status')->comment('null=unknown,0=not access,1=can access');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voter_infos', function (Blueprint $table) {
            if (Schema::hasColumn('voter_infos', 'cant_access')) {
                $table->dropColumn('cant_access');
            }
        });
    }
}
