<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStyleRequestIdToRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('recommendations')) {
            Schema::table('recommendations', function (Blueprint $table) {
                $table->integer('style_request_id');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recommendations', function (Blueprint $table) {
            //
        });
    }
}
