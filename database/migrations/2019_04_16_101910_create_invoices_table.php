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

            $table->string('number')->unique();
            $table->integer('user_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('company_id')->unsigned();

            $table->float('amount_paid', 20, 2);
            $table->float('subtotal', 20, 2);
            $table->float('total', 20, 2);
            $table->float('balance', 20, 2);

            $table->string('invoice_notes', 500);

            $table->timestamp('invoice_date')->nullable();
            $table->timestamp('due_date')->nullable();
            
            $table->string('status');

            $table->softDeletes();
            $table->timestamps();

            //$table->foreign('customer_id')->references('id')->on('customers');
            //$table->foreign('company_id')->references('id')->on('companies');
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
