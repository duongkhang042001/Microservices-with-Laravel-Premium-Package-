<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->string('ticker', 6)->nullable(false);
            $table->smallInteger('year')->unsigned()->nullable(false);

            $table->float('net_income', 20, 4)->nullable(true);
            $table->float('operating_cash_flow', 20, 4)->nullable(true);
            $table->float('capex', 20, 4)->nullable(true);
            $table->float('cash_dividends_paid', 20, 4)->nullable(true);
            $table->float('depreciation_amortization', 20, 4)->nullable(true);
            $table->float('free_cash_flow', 20, 4)->nullable(true);
            $table->float('cash_from_financing', 20, 4)->nullable(true);
            $table->float('cash_from_investing', 20, 4)->nullable(true);

            $table->unique(['ticker', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_flows');
    }
}
