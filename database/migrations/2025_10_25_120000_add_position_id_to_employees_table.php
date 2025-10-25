<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable()->after('position');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
        });

        // Backfill: create positions for existing position strings and set position_id
        $existing = DB::table('employees')
            ->whereNotNull('position')
            ->where('position', '!=', '')
            ->distinct()
            ->pluck('position');

        foreach ($existing as $posName) {
            $pos = DB::table('positions')->where('name', $posName)->first();
            if (!$pos) {
                $id = DB::table('positions')->insertGetId([
                    'name' => $posName,
                    'parent_id' => null,
                    'rank' => 0,
                    'description' => null,
                    'is_visible' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $id = $pos->id;
            }

            DB::table('employees')->where('position', $posName)->update(['position_id' => $id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });
    }
};
