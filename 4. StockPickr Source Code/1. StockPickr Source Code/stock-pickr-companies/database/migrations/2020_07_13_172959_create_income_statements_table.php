<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_statements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->string('ticker', 6)->nullable(false);
            $table->smallInteger('year')->unsigned()->nullable(false);

            $table->float('total_revenue', 20, 4)->nullable(true);
            $table->float('cost_of_revenue', 20, 4)->nullable(true);
            $table->float('gross_profit', 20, 4)->nullable(true);
            $table->float('operating_income', 20, 4)->nullable(true);
            $table->float('pretax_income', 20, 4)->nullable(true);
            $table->float('income_tax', 20, 4)->nullable(true);
            $table->float('interest_expense', 20, 4)->nullable(true);
            $table->float('research_and_development', 20, 4)->nullable(true);
            $table->float('sga', 20, 4)->nullable(true);
            $table->float('net_income', 20, 4)->nullable(true);
            $table->float('ebit', 20, 4)->nullable(true);
            $table->float('eps', 20, 4)->nullable(true);

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
        Schema::dropIfExists('income_statements');
    }
}
