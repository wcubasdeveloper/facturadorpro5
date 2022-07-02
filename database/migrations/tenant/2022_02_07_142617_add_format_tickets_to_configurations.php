<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormatTicketsToConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->boolean('show_ticket_80')->default(1);
            $table->boolean('show_ticket_58')->default(0);
            $table->boolean('show_ticket_50')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->dropColumn('show_ticket_80');
            $table->dropColumn('show_ticket_58');
            $table->dropColumn('show_ticket_50');
        });
    }
}
