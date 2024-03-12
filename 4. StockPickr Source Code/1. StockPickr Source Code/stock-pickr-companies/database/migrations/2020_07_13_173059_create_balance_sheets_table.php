<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_sheets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->string('ticker', 6)->nullable(false);
            $table->smallInteger('year')->unsigned()->nullable(false);

            $table->float('cash', 20, 4)->nullable(true);
            $table->float('current_cash', 20, 4)->nullable(true);
            $table->float('total_current_assets', 20, 4)->nullable(true);
            $table->float('net_intangible_assets', 20, 4)->nullable(true);
            $table->float('tangible_assets', 20, 4)->nullable(true);
            $table->float('short_term_investments', 20, 4)->nullable(true);
            $table->float('tradeaccounts_receivable_current', 20, 4)->nullable(true);
            $table->float('total_assets', 20, 4)->nullable(true);
            $table->float('total_equity', 20, 4)->nullable(true);
            $table->float('current_portion_of_long_term_debt', 20, 4)->nullable(true);
            $table->float('total_current_liabilities', 20, 4)->nullable(true);
            $table->float('long_term_debt', 20, 4)->nullable(true);
            $table->float('total_liabalities', 20, 4)->nullable(true);

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
        Schema::dropIfExists('balance_sheets');
    }
}
