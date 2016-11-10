<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('screen_number');
            $table->integer('target_id');
            $table->integer('operator');
            $table->text('comment');
            $table->timestamp('executed_at')->default('0000-00-00 00:00:00');
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('operation_logs');
    }
}
