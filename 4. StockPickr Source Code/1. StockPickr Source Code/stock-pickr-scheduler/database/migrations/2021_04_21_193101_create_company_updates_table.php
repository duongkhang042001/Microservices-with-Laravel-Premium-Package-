<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('company_updates', function (Blueprint $table) {
            $table->id();
            $table->string('ticker', 6)->unique();
            $table->dateTime('market_data_updated_at')->nullable(true)->default(null);
            $table->dateTime('financials_updated_at')->nullable(true)->default(null);
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_updates');
    }
}
