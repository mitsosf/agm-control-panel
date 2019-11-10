<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default("");
            $table->string('surname')->default("");
            $table->integer('role_id')->unsigned()->default(1); //Possible roles Participant, OC
            $table->string('email')->default("");
            $table->string('username')->unique();
            $table->string('password')->default("")->nullable();
            $table->string('section')->default("");
            $table->string('esncard')->default("")->nullable();
            $table->string('document')->default("")->nullable(); //ID or passport number
            $table->string('birthday')->default("")->nullable();
            $table->string('gender')->default("")->nullable();
            $table->string('phone')->default("")->nullable();
            $table->string('esn_country')->default("")->nullable();
            $table->string('photo')->default(asset('images/logo.png'));
            $table->string('tshirt')->default("")->nullable();
            $table->string('facebook')->default("")->nullable();
            $table->string('allergies')->default("")->nullable();
            $table->string('meal')->default("")->nullable();
            $table->string('comments',10000)->default("")->nullable();
            $table->string('fee')->default("0")->nullable();   //Event fee payed
            $table->dateTime('fee_date')->nullable();
            $table->boolean('rooming')->default(false);
            $table->integer('room_id')->unsigned()->nullable();
            $table->string('rooming_comments')->default("")->nullable();
            $table->string('debt')->default("0");   //Money participant owes
            $table->string('checkin')->default("0")->nullable();
            $table->string('spot_status')->default(null)->nullable();
            $table->integer('event_id')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
