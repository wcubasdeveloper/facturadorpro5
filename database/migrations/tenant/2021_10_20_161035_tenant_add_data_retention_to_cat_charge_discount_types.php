<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataRetentionToCatChargeDiscountTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('cat_charge_discount_types')->insert([
            ['id' => '62', 'active' => true, 'base' => false, 'level' => 'global', 'type' => 'discount', 'description' => 'Retención del IGV'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('cat_charge_discount_types')->where('id', '62')->delete();
    }
    
}
