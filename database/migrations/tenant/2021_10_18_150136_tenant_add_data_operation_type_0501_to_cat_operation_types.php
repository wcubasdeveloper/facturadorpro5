<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataOperationType0501ToCatOperationTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('cat_operation_types')->insert([
            ['id' => '0501', 'active' => true, 'exportation' => false, 'description' => 'Compra interna'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('cat_operation_types')->where('id', '0501')->delete();
    } 
    
}
