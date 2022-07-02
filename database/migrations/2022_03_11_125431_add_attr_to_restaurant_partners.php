<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttrToRestaurantPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_partners', function (Blueprint $table) {
            $table->char('department_id', 2)->nullable()->after('domain');
            $table->string('zone')->nullable()->after('department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_partners', function (Blueprint $table) {
            $table->dropColumn(['department_id', 'zone']);
        });
    }
}
