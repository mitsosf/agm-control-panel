<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceNumberAndAddressAndApplicationIdToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function( Blueprint $table) {
            $table->string('invoice_number')->default("")->after('phone');
            $table->string('invoice_address')->default("")->after('invoice_number');
            $table->string('application_id')->default("")->after('invoice_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('invoice_address');
        });
    }
}
