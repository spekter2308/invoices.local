<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Counter;

class CreateCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('prefix');
            $table->string('start');
            $table->string('postfix');
            $table->integer('increment');

            $table->timestamps();
        });

        Counter::create([
            'prefix' => '0000',
            'start' => 0,
            'postfix' => 0,
            'increment' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('counters');
    }
}
