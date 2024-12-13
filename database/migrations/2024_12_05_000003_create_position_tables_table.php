<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // positions table remains unchanged
        Schema::create('positions', function (Blueprint $table) {
            $table->id('position_id');
            $table->string('position_name', 255);
            $table->integer('rate_per_hour');
            $table->timestamps();
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_tables');
    }
};
