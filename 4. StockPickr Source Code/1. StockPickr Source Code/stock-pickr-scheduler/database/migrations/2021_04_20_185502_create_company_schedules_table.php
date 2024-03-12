<?php

use App\Repositories\CompanyScheduleRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('company_schedules', function (Blueprint $table) {
            $table->uuid('id');
            $table->bigIncrements('pid');

            $table->enum('event', CompanyScheduleRepository::ALL_EVENTS);

            $table->string('ticker', 6)->nullable(true);
            $table->dateTime('started_at')->nullable(false);
            $table->dateTime('finished_at')->nullable(true);

            $table->enum('state', CompanyScheduleRepository::ALL_STATES)->default(CompanyScheduleRepository::STATE_IN_PROGRESS);
            $table->json('payload')->nullable(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_schedules');
    }
}
