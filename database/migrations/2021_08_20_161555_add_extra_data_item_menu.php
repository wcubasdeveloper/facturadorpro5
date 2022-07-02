<?php

    use App\Models\System\Module;
    use App\Models\System\ModuleLevel;
    use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraDataItemMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('modules', function (Blueprint $table) {
            //
            $e = Module::find(8);

            $q = new ModuleLevel([
                'value'       => 'inventory_item_extra_data',
                'description' => 'Datos extra de items',
            ]);
            $q->setModuleId($e->id)->push();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        $q = ModuleLevel::where('value', 'inventory_item_extra_data')->first();
        if($q != null) {
            $q->delete();
        }
    }
}
