<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeniedCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('denied_companies', function (Blueprint $table) {
            $table->id();
            $table->string('ticker')->nullable(false);
            $table->longText('reason')->nullable(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('denied_companies');
    }
}
