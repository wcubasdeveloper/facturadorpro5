<?php

    use App\Models\Tenant\Module;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\DB;
    use Modules\LevelAccess\Models\ModuleLevel;

    /**
     * Class AddModulesDigemid
     */
    class AddModulesDigemid extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            $e = new Module();
            $e->setDescription('Farmacia')->setValue('digemid')->setLastOrderMenuInt();
            $e->push();
            $q = new ModuleLevel([
                                     'value' => 'digemid',
                                     'description' => 'Productos'
                                 ]);
            $q->setModuleId($e->id)->push();

        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            $e = Module::where([
                                   'description' => 'Farmacia',
                                   'value'       => 'digemid',
                               ])
                       ->first();
            if (!empty($e)) {
                $id = $e->id;
                $e->delete();
                $q = ModuleLevel::where('module_id', $id)->get();
                foreach ($q as $i) {
                    $i->delete();
                }

            }
        }
    }
