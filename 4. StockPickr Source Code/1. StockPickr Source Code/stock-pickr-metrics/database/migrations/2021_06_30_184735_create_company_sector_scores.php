<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySectorScores extends Migration
{
    public function up()
    {
        Schema::create('company_sector_scores', function (Blueprint $table) {
            $table->id();
            $table->string('ticker', 6);
            $table->foreignId('company_id');

            $table->smallInteger('debt_to_capital')->nullable(true)->unsigned();
            $table->smallInteger('current_ratio')->nullable(true)->unsigned();
            $table->smallInteger('quick_ratio')->nullable(true)->unsigned();
            $table->smallInteger('cash_to_debt')->nullable(true)->unsigned();

            $table->smallInteger('interest_to_operating_profit')->nullable(true)->unsigned();
            $table->smallInteger('long_term_debt_to_ebitda')->nullable(true)->unsigned();
            $table->smallInteger('interest_coverage_ratio')->nullable(true)->unsigned();

            $table->smallInteger('debt_to_assets')->nullable(true)->unsigned();
            $table->smallInteger('operating_cash_flow_to_current_liabilities')->nullable(true)->unsigned();
            $table->smallInteger('capex_as_percent_of_revenue')->nullable(true)->unsigned();
            $table->smallInteger('capex_as_percent_of_operating_cash_flow')->nullable(true)->unsigned();

            $table->smallInteger('payout_ratio')->nullable(true)->unsigned();

            $table->smallInteger('roic')->nullable(true)->unsigned();
            $table->smallInteger('croic')->nullable(true)->unsigned();
            $table->smallInteger('rota')->nullable(true)->unsigned();
            $table->smallInteger('roa')->nullable(true)->unsigned();
            $table->smallInteger('roe')->nullable(true)->unsigned();

            $table->smallInteger('free_cash_flow_to_revenue')->nullable(true)->unsigned();
            $table->smallInteger('net_margin')->nullable(true)->unsigned();
            $table->smallInteger('operating_margin')->nullable(true)->unsigned();
            $table->smallInteger('gross_margin')->nullable(true)->unsigned();
            $table->smallInteger('operating_cash_flow_margin')->nullable(true);
            $table->smallInteger('sga_to_gross_profit')->nullable(true)->unsigned();

            $table->smallInteger('eps_growth')->nullable(true)->unsigned();
            $table->smallInteger('net_income_growth')->nullable(true)->unsigned();
            $table->smallInteger('total_revenue_growth')->nullable(true)->unsigned();

            $table->timestamps();
            $table->unique('ticker');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_sector_scores');
    }
}
