<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('ticker', 6)->nullable(false);
            $table->string('sector')->nullable(false);

            $table->smallInteger('position')->nullable(true);
            $table->float('position_percentile', 12, 8)->nullable(true);

            $table->smallInteger('total_scores')->nullable(true)->unsigned(true);
            $table->float('total_score_percent', 12, 8)->nullable(true)->unsigned(true);
            $table->smallInteger('total_sector_scores')->nullable(true)->unsigned(true);
            $table->float('total_sector_score_percent', 12, 8)->nullable(true)->unsigned(true);

            $table->unique('ticker');
            $table->unique('position');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
