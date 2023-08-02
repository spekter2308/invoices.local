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
            $table->bigInteger('invoice_id')->unsigned();
            $table->string('item');
            $table->string('description')->nullable();
            $table->float('unitprice');
            $table->float('quantity');
            $table->double('itemtax')->default(0)->nullable();

            //for vue validation
            $table->boolean('dirty')->nullable();
            $table->boolean('correct')->nullable();


            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

        });
        /*       Schema::table('items', function (Blueprint $table) {
                    $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
                });*/

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
