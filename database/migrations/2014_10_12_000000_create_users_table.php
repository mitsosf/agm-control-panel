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
            $table->string('role_id')->default("1"); //possible roles Participant, OC
            $table->string('email')->unique();
            $table->string('username');
            $table->string('section')->default("");
            $table->string('esncard')->default("")->nullable();
            $table->string('document')->default("")->nullable(); //ID or passport number
            $table->string('birthday')->default("")->nullable();
            $table->string('gender')->default("")->nullable();
            $table->string('phone')->default("")->nullable();
            $table->string('esn_country')->default("")->nullable();
            $table->string('photo')->default("https://agmthessaloniki.org/logo_color.png");
            $table->string('tshirt')->default("")->nullable();
            $table->string('facebook')->default("")->nullable();
            $table->string('allergies')->default("")->nullable();
            $table->string('meal')->default("")->nullable();
            $table->string('comments')->default("")->nullable();
            $table->string('fee')->default("0")->nullable();   //event fee payed
            $table->dateTime('feedate')->nullable();
            $table->string('rooming')->default("0")->nullable();
            $table->string('roomingcomments')->default("")->nullable();
            $table->string('checkin')->default("0")->nullable();
            $table->string('spot_status')->default(null)->nullable();
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
