<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('ticker', 6)->unique()->nullable(false);
            $table->foreignId('sector_id')->constrained();
            $table->string('industry')->nullable();
            $table->longText('description')->nullable(true)->default(null);
            $table->integer('employees')->nullable(true)->default(null);
            $table->string('ceo')->nullable(true);


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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('companies');
        Schema::enableForeignKeyConstraints();
    }
}
