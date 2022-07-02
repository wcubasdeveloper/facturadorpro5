<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddAllowanceChargeToConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->boolean('active_allowance_charge')->default(false)->nullable()->after('include_igv');
            $table->decimal('percentage_allowance_charge', 12,2)->default(0)->nullable()->after('include_igv');
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
            $table->dropColumn('active_allowance_charge');
            $table->dropColumn('percentage_allowance_charge');
        });
    }
}
