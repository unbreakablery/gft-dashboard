<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_schedule', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('year_num');
            $table->tinyInteger('week_num');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('driver_id');
            $table->string('driver_name')->nullable();
            $table->string('tractor_id');
            $table->string('tcheck')->nullable();
            $table->string('spare_unit')->nullable();
            $table->string('fleet_net');
            $table->string('saturday')->default('OFF');
            $table->string('sunday')->default('OFF');
            $table->string('monday')->default('OFF');
            $table->string('tuesday')->default('OFF');
            $table->string('wednesday')->default('OFF');
            $table->string('thursday')->default('OFF');
            $table->string('friday')->default('OFF');
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
        Schema::dropIfExists('weekly_schedule');
    }
}
