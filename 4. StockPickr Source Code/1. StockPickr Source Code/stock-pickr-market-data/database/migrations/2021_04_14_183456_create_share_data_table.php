<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareDataTable extends Migration
{
    public function up()
    {
        Schema::create('share_data', function (Blueprint $table) {
            $table->id();

            $table->string('ticker', 6)->nullable(false)->unique();
            $table->float('price', 8, 2);
            $table->float('market_cap', 12, 4)->comment('In million');
            $table->float('shares_outstanding', 12, 4)->nullable(true)->comment('In million');
            $table->float('beta', 6, 4)->nullable(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_data');
    }
}
