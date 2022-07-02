<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;

class RegisterAppGenerateLinkToModules extends Migration
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
    }

    public static function getModuleData(): array
    {
        return [
            'value' => 'generate_link_app',
            'description' => 'Generador de link de pago',
            'sort' => 19,
            // 'order_menu' => 19,
        ];
    }

    public static function getSystemModuleConnection(): Builder
    {
        return DB::connection('system')->table('modules');
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
            DB::connection('system')->table('modules')->delete($suscriptionRow->id);
        }


    }
}
