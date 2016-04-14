<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('sid');
            //$table->timestamps();
            $table->uuid('uuid')->index()->unique();
            $table->uuid('managedBy', 64)->index(); // uuid
            $table->uuid('createdBy', 64)->index();
            $table->timestamp('createdAt')->index();
            $table->string('modifiedBy')->nullable();
            $table->timestamp('modifiedAt')->nullable();
            $table->integer('modifiedCounter')->default(0);

            $table->uuid('assignmentUuid')->index(); // The starting point in the content graph
            $table->uuid('contentUuid'); // The content

            $table->float('correctness');
            $table->float('score'); // Local score

            // @todo - Use json whenever possible
            $table->longText('contentInstance'); // Variables defined

            $table->longText('state'); // evaluations
            $table->longText('timestamps'); // timestamps
            $table->longText('evalDetails'); // evalDetails: array or Evals

            $table->foreign('assignmentUuid')->references('uuid')->on('assignments');
            $table->foreign('contentUuid')->references('uuid')->on('contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activities');
    }
}
