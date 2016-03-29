<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('sid');
            //$table->timestamps();
            $table->uuid('uuid')->index()->unique();
            $table->uuid('managedBy', 64)->index(); // uuid
            $table->uuid('createdBy', 64)->index();
            $table->timestamp('createdAt')->index();
            $table->string('modifiedBy')->nullable();
            $table->timestamp('modifiedAt')->nullable();
            $table->integer('modifiedCounter')->default(0);

            $table->timestamp('disabledAt')->nullable();
            $table->string('kind', 12)->index(); // basic, premium
            $table->string('status', 8); // registered, signedup, imported, active
            $table->string('displayName', 64);
            $table->string('primaryEmail', 64)->index();

            $table->string('roles', 64)->index();
            $table->string('imageUrl')->nullable();

            $table->timestamp('lastLogin');

            // Constraints
            //$table->primary('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounts');
    }
}
