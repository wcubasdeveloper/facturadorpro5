<?php

    use Illuminate\Database\Migrations\Migration;
    use App\Models\System\ModuleLevel;

    class AdjustInventoryItemExtraData extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            // Se ajusta el permiso del modulo correctamente ya que en el archivo database/migrations/tenant/2021_08_20_161555_add_extra_data_item_menu.php
            // se ajusta el modulo de systema y no del tenant.
            $levels = ModuleLevel::where([
                'value'       => 'inventory_item_extra_data',
                'description' => 'Datos extra de items',
            ])->orderby('id')->get();
            /** @var ModuleLevel $level */

            $i = 0;
            foreach($levels as $level){
                if($i == 0) {
                    $i = 1;
                }else{
                    $level->delete();
                }
            }


            DB::table('module_levels')->insert([
                'value' => 'inventory_item_extra_data',
                'description' => 'Datos extra de items',
                'module_id' => 8,

            ]);
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            //
            DB::table('module_levels')->whereIn('value', ['inventory_item_extra_data'])->delete();

        }
    }
