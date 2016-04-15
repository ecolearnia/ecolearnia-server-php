<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->bigIncrements('sid');
            //$table->timestamps();
            $table->uuid('uuid')->index()->unique();
            $table->uuid('managedBy', 64)->index(); // uuid
            $table->uuid('createdBy', 64)->index();
            $table->timestamp('createdAt')->index();
            $table->string('modifiedBy')->nullable();
            $table->timestamp('modifiedAt')->nullable();
            $table->integer('modifiedCounter')->default(0);

            $table->timestamp('lastInteraction')->nullable();

            $table->uuid('outsetCNodeUuid')->index(); // The starting point in the content graph
            $table->uuid('lastCNodeUuid')->nullable(); // The last content that was delivered to the student
            $table->uuid('activityHeadUuid')->nullable(); // The first activity
            $table->uuid('activityTailUuid')->nullable(); // The last activity that was instantiated
            $table->uuid('activeActivityUuid')->nullable(); // The last activity that was touched, it is usually the last activity but not necessarly always

            $table->integer('stats_activitiesCount')->default(0);
            $table->integer('stats_timeSpent')->default(0);
            $table->integer('stats_corrects')->default(0);
            $table->integer('stats_incorrects')->default(0);
            $table->integer('stats_partialcorrects')->default(0);
            $table->float('stats_score')->default(0); // Local score

            $table->longText('config')->nullable(); // Policy, etc.
            $table->longText('state_itemEvalBriefs')->nullable(); // evaluations

        });

        Schema::table('assignments', function ($table) {
            $table->foreign('outsetCNodeUuid')->references('uuid')->on('contents');
            $table->foreign('lastCNodeUuid')->references('uuid')->on('contents');
            //$table->foreign('activityTailUuid')->references('uuid')->on('activities');
            //$table->foreign('activeActivityUuid')->references('uuid')->on('activities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('assignments');
    }
}
