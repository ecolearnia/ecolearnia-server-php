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
            $table->uuid('parentUuid')->index();
            $table->uuid('copiedFromUuid')->index();

            $table->string('version', 64);
            $table->string('type', 12)->index(); // node - internal node, item - content item

            $table->string('meta_subject', 64)->index(); // Eg. Math
            $table->string('meta_subjectArea', 64)->index(); // Eg. Arithmeti
            $table->string('meta_domainCodeSource', 32)->index(); // Eg. CommonCore
            $table->string('meta_domainCode', 64)->index(); // "CCSS.MATH.CONTENT.1.NBT.B.2.A"
            $table->string('meta_authors');
            $table->string('meta_locale', 12)->index(); // EN_us
            $table->string('meta_title'); //
            $table->integer('meta_expectedDuration'); // average duration in minutes
            $table->tinyInteger('meta_difficulty'); // Difficulty value range [0, 100]
            $table->string('meta_license')->default('cc-by-nc-sa/4.0'); // Creative Commons: http://creativecommons.org/licenses/by-nc-sa/4.0/
            // @todo - add: pre requisites

            //$table->json('content'); // Either array of children or the ContentdDefinition
            // JSON not supported in my local PHP installation, probably similar
            // So using longText instead
            $table->longText('content'); 
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
