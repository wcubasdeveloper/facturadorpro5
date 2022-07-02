<?php

    use App\Models\System\Module;
    use App\Models\System\ModuleLevel;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    /**

     * Class AddDocumentaryRequirements
     */
    class AddDocumentaryRequirements extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::table('modules', function (Blueprint $table) {
                //
                $e = Module::find(16);

                $q = new ModuleLevel([
                                         'value'       => 'documentary_requirements',
                                         'description' => 'Requerimientos',
                                     ]);
                $q->setModuleId($e->id)->push();
            });
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
