<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Query\Builder;

    class CreateAppSuscription extends Migration
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
                $suscriptionRow = self::getSystemModuleConnection()->insert($suscriptionData);

            }
            $suscriptionRow = self::getSystemModuleConnection()->where($suscriptionData)->first();
            if ($suscriptionRow != null) {
                $levels = $this->getModuleLevelData($suscriptionRow->id);
                foreach ($levels as $level) {
                    $suscriptionLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                    if ($suscriptionLevelRow === null) {
                        $suscriptionLevelRow = self::getSystemModuleLevelConnection()->insert($level);

                    }
                }
            }


        }

        public static function getModuleData(): array
        {
            return [
                'value' => 'suscription_app',
                'description' => 'Suscriptiones',
                'sort' => 16,
                // 'order_menu' => 16,

            ];
        }

        public static function getSystemModuleConnection(): Builder
        {
            return DB::connection('system')->table('modules');
        }

        public function getModuleLevelData($module_id): array
        {
            if (empty($module_id)) {

                echo("No se encuentra el id de modulo\n");
            }
            $data = [];
            $data [] = [
                'value' => 'suscription_app_client',
                'description' => 'Cliente',
                'module_id' => $module_id,

            ];
            $data [] = [
                'value' => 'suscription_app_service',
                'description' => 'Servicio',
                'module_id' => $module_id,

            ];
            $data [] = [
                'value' => 'suscription_app_payments',
                'description' => 'Pagos',
                'module_id' => $module_id,

            ];
            $data [] = [
                'value' => 'suscription_app_plans',
                'description' => 'Planes',
                'module_id' => $module_id,

            ];
            /*
            $data [] = [
                'value' => 'suscription_app_payments',
                'description' => 'Pagos',
                'module_id' => $module_id,

            ];
            */
            return $data;
        }

        public static function getSystemModuleLevelConnection(): Builder
        {
            return DB::connection('system')->table('module_levels');
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
                $levels = $this->getModuleLevelData($suscriptionRow->id);
                foreach ($levels as $level) {
                    $suscriptionLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                    if ($suscriptionLevelRow != null) {
                        DB::connection('system')->table('module_levels')->delete($suscriptionLevelRow->id);
                    }
                }
                DB::connection('system')->table('modules')->delete($suscriptionRow->id);
            }


        }
    }
