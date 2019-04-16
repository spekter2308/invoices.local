<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('bulk_action');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('company_id');
            $table->float('total');
            $table->float('balance');
            $table->string('status');
            $table->date('due_date');
            $table->string('item');
            $table->string('description');
            $table->integer('unit_price');
            $table->integer('quantity');
            $table->string('invoice_notes')->nullable();
            $table->string('currency');
            $table->float('second_tax');



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
        Schema::dropIfExists('invoices');
    }
}
