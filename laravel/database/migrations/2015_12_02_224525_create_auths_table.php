<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auths', function (Blueprint $table) {
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
            $table->string('authSource', 12)->index(); // (aka provider) local, google, linkedin, etc.
            $table->string('authId', 64)->index(); // unique id in the source
            // The raw information obtained from the auth source in JSON format
            $table->longText('authCredentialsRaw')->nullable();
            $table->string('status')->index(); // Unused, just set to 1

            $table->timestamp('sessionTimestamp');
            $table->string('rememberToken', 64)->nullable();
            $table->integer('authFailCounter')->default(0);
            $table->string('username')->nullable(); // Applicable if authSource is 'local'
            $table->string('security_password', 32)->nullable();
            $table->string('security_activationCode', 64)->nullable();
            $table->string('security_securityQuestion', 256)->nullable();
            $table->string('security_securityAnswer', 64)->nullable();

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
        Schema::drop('auths');
    }
}
