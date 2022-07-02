<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Query\Builder;

    class CreateAppProduction extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            $suscriptionData = self::getModuleData();
            $suscriptionRow = self::getSystemModuleConnection()->where($suscriptionData)->first();
            if ($suscriptionRow === null) {
                self::getSystemModuleConnection()->insert($suscriptionData);
            }


        }

        public static function getModuleData(): array
        {
            return [
                'value' => 'production_app',
                'description' => 'Producción',
                 // 'sort' => 17,
                 'order_menu' => 17,

            ];
        }

        public static function getSystemModuleConnection(): Builder
        {
            return DB::connection('tenant')->table('modules');
        }

        public function getModuleLevelData($module_id): array
        {
            if (empty($module_id)) {

                echo("No se encuentra el id de modulo\n");
            }
            $data = [];
            $data [] = [
                'value' => 'production_menu',
                'description' => 'Menu de producción',
                'module_id' => $module_id,

            ];
            return $data;
        }

        public static function getSystemModuleLevelConnection(): Builder
        {
            return DB::connection('tenant')->table('module_levels');
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            $suscriptionData = self::getModuleData();

            $suscriptionRow = self::getSystemModuleConnection()->where($suscriptionData)->first();

        }
    }
