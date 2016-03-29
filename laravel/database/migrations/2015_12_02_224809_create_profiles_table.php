<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('sid');
            //$table->timestamps();
            $table->uuid('uuid')->index()->unique();
            $table->uuid('managedBy', 64)->index(); // uuid
            $table->uuid('createdBy', 64)->index();
            $table->timestamp('createdAt')->index();
            $table->string('modifiedBy')->nullable();
            $table->timestamp('modifiedAt')->nullable();
            $table->integer('modifiedCounter')->default(0);

            $table->uuid('accountUuid')->index();
            $table->string('familyName', 64)->index(); // required
            $table->string('givenName', 64)->index(); // required
            $table->string('middleName', 64)->nullable();
            $table->text('highlight')->nullable();

            $table->date('dob')->nullable();
            $table->string('gender', 8)->nullable();
            $table->string('phone', 16)->nullable();
            $table->string('mobile', 16)->nullable();
            $table->string('timezone', 16)->nullable();
            $table->string('permalink', 125)->nullable();
            //$table->string('locale', 8)->nullable();

            $table->string('language', 8)->nullable();

            $table->string('address_countryCode', 8)->index()->nullable();
            $table->string('address_stateCode', 32)->nullable();
            $table->string('address_city', 64)->nullable();
            $table->string('address_street', 256)->nullable();
            $table->string('address_postalCode', 32)->nullable();

            // Constraints
            //$table->primary('uuid');
            $table->foreign('accountUuid')->references('uuid')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles');
    }
}
