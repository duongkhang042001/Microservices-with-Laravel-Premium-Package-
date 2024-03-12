<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPeersTable extends Migration
{
    public function up()
    {
        Schema::create('company_peers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained()->onDelete('CASCADE');
            $table->bigInteger('peer_id')->unsigned();
            $table->foreign('peer_id')->references('id')->on('companies');
            $table->string('ticker', 6);
            $table->unique(['company_id', 'peer_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_peers');
    }
}
