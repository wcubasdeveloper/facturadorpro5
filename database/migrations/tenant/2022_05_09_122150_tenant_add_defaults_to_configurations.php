<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDefaultsToConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('configurations')
            ->where('id', '1')
            ->update([
                'top_menu_a_id' => 1,
                'top_menu_b_id' => 15,
                'top_menu_c_id' => 76,
                'login' => '{"type":"image","image":"http:\/\/'.config('tenant.app_url_base').'\/images\/fondo-5.svg","position_form":"right","show_logo_in_form":false,"position_logo":"top-left","show_socials":false,"facebook":null,"twitter":null,"instagram":null,"linkedin":null}'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
