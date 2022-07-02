<?php

    use App\Models\Tenant\Module;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\DB;
    use Modules\LevelAccess\Models\ModuleLevel;
    /**
     * Class AddModulesDocymentaryRequirements
     */
    class AddModulesDocymentaryRequirements  extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            $e = Module::find(16);

            $q = new ModuleLevel([
                                     'value'       => 'documentary_requirements',
                                     'description' => 'Requerimientos',
                                 ]);
            $q->setModuleId($e->id)->push();

        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            $q = ModuleLevel::where('value', 'documentary_requirements')->first();
            $q->delete();
        }
    }
