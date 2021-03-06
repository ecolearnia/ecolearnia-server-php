<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('sid');
            //$table->timestamps();
            $table->uuid('uuid')->index()->unique();
            $table->uuid('managedBy', 64)->index(); // uuid
            $table->uuid('createdBy', 64)->index();
            $table->timestamp('createdAt')->index();
            $table->string('modifiedBy')->nullable();
            $table->timestamp('modifiedAt')->nullable();
            $table->integer('modifiedCounter')->default(0);

            $table->uuid('realmUuid')->index();
            $table->uuid('parentUuid')->nullable()->index();
            $table->uuid('copiedFromUuid')->nullable()->index();

            $table->tinyInteger('publishStatus')->index()->default(0); // 0=> in-progress, 1=> review-pending, 9 => ready
            $table->string('version', 64)->nullable();
            $table->string('type', 12)->index(); // container - internal node, item - content item
            $table->boolean('assignable')->index()->default(false); // true -> is listed in the mission catalog

            $table->string('meta_subject', 64)->index(); // Eg. Math
            $table->string('meta_subjectArea', 64)->index(); // Eg. Arithmeti
            $table->string('meta_domainCodeSource', 32)->nullable()->index(); // Eg. CommonCore
            $table->string('meta_domainCode', 64)->nullable()->index(); // "CCSS.MATH.CONTENT.1.NBT.B.2.A"
            $table->string('meta_authors')->nullable();
            $table->string('meta_locale', 12)->index(); // EN_us
            $table->string('meta_title'); //
            $table->mediumText('meta_description')->nullable(); //
            $table->integer('meta_expectedDuration')->nullable(); // average duration in minutes
            $table->tinyInteger('meta_difficulty')->nullable(); // Difficulty value range [0, 100]
            $table->string('meta_license')->default('cc-by-nc-sa/4.0'); // Creative Commons: http://creativecommons.org/licenses/by-nc-sa/4.0/
            // @todo - add: pre requisites

            //$table->json('content'); // Either array of children or the ContentdDefinition
            // JSON not supported in my local PHP installation, probably similar
            // So using longText instead
            $table->longText('content');

            // Configurtion e.g. The randomizer, number of activities
            // JSON: process: {beforeInstantiation}, numActivities,
            $table->longText('config')->nullable(); // Policy, etc.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contents');
    }
}
