<?php

namespace Modules\Pos\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Pos\Traits\TipTrait;
use App\Models\Tenant\{
    Document,
    SaleNote,
};

class TipServiceProvider extends ServiceProvider
{

    use TipTrait;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->document();
        $this->sale_note();
    }

        
    /**
     * 
     * Eventos del modelo Document - CPE
     *
     * @return void
     */
    public function document()
    {

        // Registrar
        Document::created(function($document){

            $tip = request()->tip;
            $this->createTip($document, $tip);

        });
        

        // Actualizar
        Document::updated(function($document){

            // datos a actualizar
            $update_data = $this->getDataForUpdate($document);
            $this->updateTip($document, $update_data);

        });
        
    }


    /**
     * 
     * Eventos del modelo SaleNote - Nota de venta
     *
     * @return void
     */
    public function sale_note()
    {

        // Registrar
        SaleNote::created(function($sale_note){

            $tip = $this->getTipFromRequest(request()->all());
            $this->createTip($sale_note, $tip);
            
        });


        // Actualizar
        SaleNote::updated(function($sale_note){

            // datos a actualizar
            $update_data = $this->getDataForUpdate($sale_note);
            $this->updateTip($sale_note, $update_data);

        });

    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

}
