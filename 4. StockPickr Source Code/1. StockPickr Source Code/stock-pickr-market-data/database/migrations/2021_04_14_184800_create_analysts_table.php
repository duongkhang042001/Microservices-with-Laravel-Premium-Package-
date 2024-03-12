<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalystsTable extends Migration
{
    public function up()
    {
        Schema::create('analysts', function (Blueprint $table) {
            $table->id();

            $table->string('ticker', 6)->unique();

            $table->tinyInteger('buy')->unsigned()->nullable(true)->default(null);
            $table->tinyInteger('hold')->unsigned()->nullable(true)->default(null);
            $table->tinyInteger('sell')->unsigned()->nullable(true)->default(null);

            $table->float('price_target_low', 8, 2)->unsigned()->nullable(true)->default(null);
            $table->float('price_target_average', 8, 2)->unsigned()->nullable(true)->default(null);
            $table->float('price_target_high', 8, 2)->unsigned()->nullable(true)->default(null);

            $table->tinyInteger('number_of_analysts')->unsigned()->nullable(true)->default(null);
            $table->date('rating_date')->nullable(true)->default(null);

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
        Schema::dropIfExists('analysts');
    }
}
