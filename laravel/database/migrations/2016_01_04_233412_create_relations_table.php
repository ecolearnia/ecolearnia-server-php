<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->bigIncrements('sid');
            //$table->timestamps();
            $table->uuid('uuid')->index()->unique();
            $table->uuid('managedBy', 64)->index(); // uuid
            $table->uuid('createdBy', 64)->index();
            $table->timestamp('createdAt')->index();
            $table->uuid('modifiedBy', 64)->nullable();
            $table->timestamp('modifiedAt')->nullable();
            $table->integer('modifiedCounter')->default(0);

            $table->uuid('account1Uuid', 64)->index();
            $table->string('role1', 64)->index(); // mentor
            $table->uuid('account2Uuid', 64)->index();
            $table->string('role2', 64)->index(); // mentee
            $table->string('relationship', 32)->index(); // mentoring, teaching,
            $table->tinyInteger('strenght')->default(1); // 0 ~ 100, 100 strong relationship
            $table->timestamp('lastInteraction');

            $table->foreign('account1Uuid')->references('uuid')->on('accounts');
            $table->foreign('account2Uuid')->references('uuid')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('relations');
    }
}
