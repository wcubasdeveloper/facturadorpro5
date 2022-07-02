<?php

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeValueShowItemsOnlyUserStablishment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $config = DB::connection('tenant')->table('configurations')->first();
        if(!empty($config)) {
            $config->show_items_only_user_stablishment = 1;
            DB::connection('tenant')->table('configurations')->update((array)$config);
        }

    }

}
