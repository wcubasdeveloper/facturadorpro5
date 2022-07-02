<?php

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
    use Modules\DocumentaryProcedure\Models\DocumentaryOffice;

    class CreateAdjustToDocymentaryOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this
            ->setItem('Caja')
            ->setItem('Procesos')
            ->setItem('Seguimiento')
            ->setItem('Validacion')
            ;

    }

    public function setItem($item =''){

        if(empty($item)) return $this;

        $items = DocumentaryOffice::where('name', 'like', "%".$item."%")->first();
        if (empty($items)) {
            $items = new DocumentaryOffice([
                                               'description' => '',
                                               'active'      => 1,
                                               'name'        => $item,
                                               'parent_id'   => 0,
                                               'order'       => 0,
                                           ]);
        }
        $items->active = 1;
        $items->parent_id = 0;
        $items->order = 0;
        $items->push();
        return $this;
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
