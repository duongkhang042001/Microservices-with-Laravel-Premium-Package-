<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsTable extends Migration
{
    public function up()
    {
        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->string('ticker', 6)->nullable(false);
            $table->foreignId('company_id');
            $table->string('chart', 30)->nullable(false);

            $table->json('data')->nullable(false);
            $table->json('years')->nullable(false);

            $table->unique(['ticker', 'chart']);

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
        Schema::dropIfExists('charts');
    }
}
