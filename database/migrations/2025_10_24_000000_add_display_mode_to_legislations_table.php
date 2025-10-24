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
        Schema::table('legislations', function (Blueprint $table) {
            // add display_mode: 'group' or 'individual' (default to group for backward compatibility)
            $table->enum('display_mode', ['group', 'individual'])->default('group')->after('link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legislations', function (Blueprint $table) {
            $table->dropColumn('display_mode');
        });
    }
};
