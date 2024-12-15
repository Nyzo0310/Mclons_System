<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id('holiday_id');
            $table->string('description');
            $table->date('holiday_date');
            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::dropIfExists('holidays');
    }
}

