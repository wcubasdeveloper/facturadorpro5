<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Query\Builder;

    class CreateAppFullSuscription extends Migration
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
            $suscriptionRow = self::getSystemModuleConnection()->where($suscriptionData)->first();
            if ($suscriptionRow != null) {
                /*$levels = $this->getModuleLevelData($suscriptionRow->id);
                foreach ($levels as $level) {
                    $suscriptionLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                    if ($suscriptionLevelRow === null) {
                        self::getSystemModuleLevelConnection()->insert($level);
                    }
                }*/
            }


        }

        public static function getModuleData(): array
        {
            return [
                'value' => 'full_suscription_app',
                'description' => 'Suscripción Servicios SAAS',
                // 'sort' => 16,
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
                'value' => 'full_suscription_app_client',
                'description' => 'Cliente',
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
            if ($suscriptionRow != null) {
                /*$levels = $this->getModuleLevelData($suscriptionRow->id);
                foreach ($levels as $level) {
                    $suscriptionLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                    if ($suscriptionLevelRow != null) {

                        DB::connection('tenant')->table('module_levels')->delete($suscriptionLevelRow->id);

                    }
                }
                */
                DB::connection('tenant')->table('modules')->delete($suscriptionRow->id);
            }


        }
    }
