<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->float('rating', 4, 1)->unsigned(true);
            $table->longText('comment')->nullable(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_ratings');
    }
}
