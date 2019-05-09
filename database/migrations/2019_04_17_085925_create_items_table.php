<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('invoice_id');
            $table->string('item');
            $table->string('description')->nullable();
            $table->float('unitprice');
            $table->float('quantity');
            $table->double('tax')->default(0)->nullable();

            //for vue validation
            $table->boolean('dirty')->nullable();
            $table->boolean('correct')->nullable();

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
        Schema::dropIfExists('items');
    }
}
