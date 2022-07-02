<?php

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class AddConfigurationModuleToAdmin extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {

            $admin_id = 0;
            $e = DB::table('modules')
                ->where('value', 'configuration')
                ->first();
            $s = [];
            if (!empty($e)) {
                $s[] = $this->DbModuleLevel($e->id, 'configuration_company', "Empresa", true);
                $s[] = $this->DbModuleLevel($e->id, 'configuration_advance', "Avanzado", true);
                $s[] = $this->DbModuleLevel($e->id, 'configuration_visual', "Visual", true);
            }


        }

        public function DbModuleLevel($config_id, $module_name, $description, $create = true)
        {
            $toSearch = [
                'module_id' => $config_id,
                'value' => $module_name,
                'description' => $description,
            ];
            $e = DB::table('module_levels')
                ->where($toSearch)
                ->first();
            if (empty($e)) {
                DB::table('module_levels')
                    ->insert($toSearch);
                $e = DB::table('module_levels')
                    ->where($toSearch)
                    ->first();
            }
            if (!empty($e)) {
                if ($create != true) {
                    DB::table('module_levels')
                        ->where($toSearch)
                        ->delete();
                }
            }
            return $e;
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {

            $admin_id = 0;
            $e = DB::table('modules')
                ->where('value', 'configuration')
                ->first();
            $s = [];
            if (!empty($e)) {
                $s[] = $this->DbModuleLevel($e->id, 'configuration_company', "Empresa",false);
                $s[] = $this->DbModuleLevel($e->id, 'configuration_advance', "Avanzado",false);
                $s[] = $this->DbModuleLevel($e->id, 'configuration_visual', "Visual",false);
            }
        }
    }
