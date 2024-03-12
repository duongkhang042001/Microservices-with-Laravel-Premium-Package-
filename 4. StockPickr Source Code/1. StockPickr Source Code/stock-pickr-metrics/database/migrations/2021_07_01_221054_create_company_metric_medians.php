<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyMetricMedians extends Migration
{
    public function up()
    {
        Schema::create('company_metric_medians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('ticker', 6)->nullable(false);

            $table->float('debt_to_capital', 10, 4)->nullable(false)->unsigned();
            $table->float('current_ratio', 10, 4)->nullable(false)->unsigned();
            $table->float('quick_ratio', 10, 4)->nullable(false)->unsigned();
            $table->float('cash_to_debt', 10, 4)->nullable(false)->unsigned();

            $table->float('interest_to_operating_profit', 10, 4)
                ->nullable(true)
                ->unsigned()
                ->comment('Akkor lehet NULL, ha interest bevétel van, és nincs operating profit');

            $table->float('long_term_debt_to_ebitda', 10, 4)->nullable(false)->unsigned();
            $table->float('interest_coverage_ratio', 10, 4)
                ->nullable(true)
                ->comment('Akkor lehet NULL, ha interest bevétel van, és negatív ebit. Akkor lehet negatív, ha interest kiadás van és negatív ebit.');

            $table->float('debt_to_assets', 10, 4)->nullable(false)->unsigned();
            $table->float('operating_cash_flow_to_current_liabilities', 10, 4)->nullable(false)->unsigned();
            $table->float('capex_as_percent_of_revenue', 10, 4)->nullable(false)->unsigned();
            $table->float('capex_as_percent_of_operating_cash_flow', 10, 4)->nullable(false)->unsigned();

            $table->float('payout_ratio', 10, 4)
                ->nullable(true)
                ->unsigned()
                ->comment('Akkor lehet NULL, ha osztalékot nem fizető cégről van szó');

            $table->float('roic', 10, 4)->nullable(false);
            $table->float('croic', 10, 4)->nullable(false);
            $table->float('rota', 10, 4)->nullable(false);
            $table->float('roa', 10, 4)->nullable(false);
            $table->float('roe', 10, 4)->nullable(false);

            $table->float('free_cash_flow_to_revenue', 10, 4)->nullable(false);
            $table->float('net_margin', 10, 4)->nullable(false);
            $table->float('operating_margin', 10, 4)->nullable(false);
            $table->float('gross_margin', 10, 4)->nullable(false);
            $table->float('operating_cash_flow_margin', 10, 4)->nullable(false);
            $table->float('sga_to_gross_profit', 10, 4)->nullable(false)->unsigned();

            $table->float('eps_growth', 10, 4)->nullable(true);
            $table->float('net_income_growth', 10, 4)->nullable(true);
            $table->float('total_revenue_growth', 10, 4)->nullable(true);

            $table->unique(['company_id', 'ticker']);

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
        Schema::dropIfExists('company_metric_medians');
    }
}
