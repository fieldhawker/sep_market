<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->integer('price');
            $table->text('caption');
            $table->integer('status');
            $table->integer('items_status');
            $table->timestamp('started_at')->default('0000-00-00 00:00:00');
            $table->timestamp('ended_at')->default('0000-00-00 00:00:00');
            $table->integer('delivery_charge');
            $table->integer('delivery_plan');
            $table->integer('pref');
            $table->integer('delivery_date');
            $table->text('comment');
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
