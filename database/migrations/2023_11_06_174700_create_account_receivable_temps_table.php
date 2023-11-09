<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountReceivableTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_receivable_temps', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->string('date');
            $table->string('due_date');
            $table->string('outlet_code');
            $table->string('outlet_name');
            $table->decimal('amount', 19);
            $table->string('salesman');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_receivable_temps');
    }
}
