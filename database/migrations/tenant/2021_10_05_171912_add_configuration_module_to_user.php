<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\DB;

    class AddConfigurationModuleToUser extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {

            $adminUser = DB::table('users')
                ->orderBy('id', 'ASC')
                ->select('id')
                ->first();
            $admin_id = 0;
            if (!empty($adminUser)) {
                $admin_id = $adminUser->id;
            }
            $e = DB::table('modules')
                ->where('value', 'configuration')
                ->first();
            $s = [];
            if (!empty($e)) {
                $s[] = $this->DbModuleLevel($e->id, 'configuration_company', "Empresa", $admin_id, true);
                $s[] = $this->DbModuleLevel($e->id, 'configuration_advance', "Avanzado", $admin_id, true);
                $s[] = $this->DbModuleLevel($e->id, 'configuration_visual', "Visual", $admin_id, true);
                $this->DbModuleUser($admin_id, $e->id,true);
            }

        }

        public function DbModuleLevel($config_id, $module_name, $description, $user_id, $create = true)
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
                $id = DB::table('module_levels')
                    ->insertGetId($toSearch);
                $e = DB::table('module_levels')
                    ->where($toSearch)
                    ->first();

            }
            if (!empty($e)) {
                if ($create != true) {
                    DB::table('module_levels')
                        ->where($toSearch)
                        ->delete();
                    $this->DbModuleLeveUser($user_id, $e->id, $create);
                } else {
                    $this->DbModuleLeveUser($user_id, $e->id,true);
                }
            }
            return $e;
        }

        public function DbModuleLeveUser($user = 0, $module_level_id = 0, $create = true)
        {
            // if ($user == 0 || $module_level_id == 0) return null;

            $toSearch = [
                'module_level_id' => $module_level_id,
                'user_id' => $user,
            ];
            $e = DB::table('module_level_user')
                ->where($toSearch)
                ->first();
            if (empty($e)) {
               $id =  DB::table('module_level_user')
                    ->insertGetId($toSearch);
                $e = DB::table('module_level_user')
                    ->where($toSearch)
                    ->first();
            }
            if (!empty($e)) {
                if ($create == false) {
                    DB::table('module_level_user')
                        ->where($toSearch)
                        ->delete();
                }
            }
            return $e;

        }

        public function DbModuleUser($user = 0, $module_id = 0, $create = true)
        {
            if ($user == 0 || $module_id == 0) return null;

            $toSearch = [
                'module_id' => $module_id,
                'user_id' => $user,
            ];
            $e = DB::table('module_user')
                ->where($toSearch)
                ->first();
            if (empty($e)) {
                $id = DB::table('module_user')
                    ->insertGetId($toSearch);
                $e = DB::table('module_user')
                    ->where($toSearch)
                    ->first();

            }
            if (!empty($e)) {
                if ($create == false) {
                    DB::table('module_user')
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
            $adminUser = DB::table('users')
                ->orderBy('id', 'ASC')
                ->select('id')
                ->first();
            $admin_id = 0;
            if (!empty($adminUser)) {
                $admin_id = $adminUser->id;
            }
            $e = DB::table('modules')
                ->where('value', 'configuration')
                ->first();
            $s = [];
            if (!empty($e)) {
                $s[] = $this->DbModuleLevel($e->id, 'configuration_company', "Empresa", $admin_id, false);
                $s[] = $this->DbModuleLevel($e->id, 'configuration_advance', "Avanzado", $admin_id, false);
                $s[] = $this->DbModuleLevel($e->id, 'configuration_visual', "Visual", $admin_id, false);
                $this->DbModuleUser($admin_id, $e->id, false);
            }
        }
    }
