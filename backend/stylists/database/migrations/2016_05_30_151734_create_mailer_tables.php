<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailerTables extends Migration
{

    CONST TABLE_MASTER_LIST = "mailer_master_list";
    CONST TABLE_MAILER_TYPE = "mailer_type";
    CONST TABLE_UNSUBCRIPTION = "unsubscription";
    CONST TABLE_CAMPAIGN = "campaign";
    CONST TABLE_CAMPAIGN_MAILER_LIST = "campaign_mailer_list";
    CONST TABLE_CAMPAIGN_MAILER_TRACKER = "campaign_mailer_tracker";


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** Mailer master list **/
        Schema::create(self::TABLE_MASTER_LIST, function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('email', 50)->unique();
            $table->string('name', 50);
            $table->enum('source', array('INTERNAL', 'EXTERNAL'));
            $table->timestamps();
        });

        /**  Mailer Type **/
        Schema::create(self::TABLE_MAILER_TYPE, function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('type', 50);
            $table->timestamp('created_at');
        });

        Schema::create(self::TABLE_UNSUBCRIPTION, function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('email', 50);
            $table->bigInteger('mailer_type_id')->unsigned();
            $table->unique(array('email',  'mailer_type_id'));
            $table->foreign('mailer_type_id')->references('id')->on('mailer_type');
            $table->timestamps();
        });

        Schema::create(self::TABLE_CAMPAIGN, function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('campaign_name', 100);
            $table->string('sender_email', 50);
            $table->string('sender_name', 50);
            $table->string('mail_subject', 512);
            $table->text('non_prepare_message');
            $table->text('message');
            $table->dateTime('published_on');
            $table->timestamps();
        });

        Schema::create(self::TABLE_CAMPAIGN_MAILER_LIST, function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('email', 50);
            $table->string('name', 50);
            $table->bigInteger('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('id')->on('campaign');
            $table->boolean('is_sent')->default(false);
            $table->dateTime('sent_at');
            $table->boolean('is_open')->default(false);
            $table->dateTime('opened_at');
            $table->boolean('is_clicked')->default(false);
            $table->timestamp('created_at');
        });

        Schema::create(self::TABLE_CAMPAIGN_MAILER_TRACKER, function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->string('email', 50);
            $table->string('url', 512);
            $table->enum('event', array('OPENED', 'CLICKED'));
            $table->foreign('campaign_id')->references('id')->on('campaign');
            $table->timestamp('created_at');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::TABLE_MASTER_LIST);
        Schema::drop(self::TABLE_UNSUBCRIPTION);
        Schema::drop(self::TABLE_MAILER_TYPE);
        Schema::drop(self::TABLE_CAMPAIGN_MAILER_TRACKER);
        Schema::drop(self::TABLE_CAMPAIGN_MAILER_LIST);
        Schema::drop(self::TABLE_CAMPAIGN);

    }
}