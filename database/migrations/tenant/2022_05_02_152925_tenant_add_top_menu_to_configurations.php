<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTopMenuToConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->unsignedInteger('top_menu_a_id')->nullable();
            $table->unsignedInteger('top_menu_b_id')->nullable();
            $table->unsignedInteger('top_menu_c_id')->nullable();
            $table->unsignedInteger('top_menu_d_id')->nullable();
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
            $table->dropColumn('top_menu_a_id');
            $table->dropColumn('top_menu_b_id');
            $table->dropColumn('top_menu_c_id');
            $table->dropColumn('top_menu_d_id');
        });
    }
}
