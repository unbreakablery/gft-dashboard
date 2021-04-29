<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinehaulDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linehaul_drivers', function (Blueprint $table) {
            $table->id();
            $table->char('driver_id', 255);
            $table->char('driver_name', 255);
            $table->float('fixed_rate', 8, 4)->nullable();
            $table->float('price_per_mile', 8, 4)->nullable();
            $table->tinyInteger('work_status')->default('1');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linehaul_drivers');
    }
}
