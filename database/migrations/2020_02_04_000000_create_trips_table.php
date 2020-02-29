<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('driver_id')->unsigned();
            $table->bigInteger('truck_id')->unsigned();
            $table->boolean('loaded');
            $table->point('origin');
            $table->point('destiny');
            $table->dateTime('trip_date')->default(Carbon::now());
            $table->softDeletes();
            
            $table->foreign('driver_id')
                ->on('drivers')
                ->references('id');
            $table->foreign('truck_id')
                ->on('trucks')
                ->references('id');
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
