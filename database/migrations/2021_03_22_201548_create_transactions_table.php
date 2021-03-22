<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaction');
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger('payer');
            $table->unsignedBigInteger('payee');
            $table->timestamps();
        });

        Schema::table('transactions', function($table) {
            $table->foreign('payer')->references('id_user')->on('users');
            $table->foreign('payee')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
