<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('position_parents', function (Blueprint $table) {
            $table->id();
            // child position
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            // parent position
            $table->foreignId('parent_id')->constrained('positions')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['position_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_parents');
    }
};
