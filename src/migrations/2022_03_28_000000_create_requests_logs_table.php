<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests_logs', function (Blueprint $table) {
            $table->id()->index();
            $table->string('endpoint')->nullable()->index();
            $table->string('method')->nullable()->index();
            $table->string('code')->nullable()->index();
            $table->json('request_data')->nullable();
            $table->json('request_headers')->nullable();
            $table->json('response_data')->nullable();
            $table->string('from_endpoint')->nullable()->index();
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
        Schema::dropIfExists('requests_logs');
    }
}
