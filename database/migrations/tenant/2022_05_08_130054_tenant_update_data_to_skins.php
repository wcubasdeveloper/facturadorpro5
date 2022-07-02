<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantUpdateDataToSkins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('skins')
            ->where('name', 'Default')
            ->update([
                'filename' => 'default.css'
            ]);

        DB::table('skins')
            ->where('name', 'Light')
            ->update([
                'filename' => 'light.css'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skins', function (Blueprint $table) {
            //
        });
    }
}
