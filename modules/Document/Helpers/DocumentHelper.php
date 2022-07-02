<?php

namespace Modules\Document\Helpers;

use Exception;
use Carbon\Carbon;
use App\Models\Tenant\{
    Configuration,
    Document
};
use Hyn\Tenancy\Environment;
use App\Models\System\Client;


class DocumentHelper
{
    
    /**
     * Obtener fecha de ciclo de facturacion desde client (system), relacionado al tenant
     */
    public static function getStartBillingCycleFromSystem()
    {
        $tenancy = app(Environment::class);
        $hostname = $tenancy->hostname();
        $client = Client::select('start_billing_cycle')->where('hostname_id', $hostname->id)->first();

        return $client->start_billing_cycle;
    }

            
    /**
     * 
     * Validar si los documentos emitidos superan el limite permitido por el plan (ciclo facturacion)
     *
     * Usado en: 
     * App\Providers\LockedEmissionProvider
     * App\Http\Controllers\Tenant\DocumentController
     * 
     * @param  $configuration
     * @return array
     */     
    public static function exceedLimitDocuments($configuration = null)
    {

        $configuration = $configuration ?? Configuration::firstOrFail();

        //cantidad limite de documentos permitidos para emitir (0 = ilimitado)
        $limit_documents = $configuration->limit_documents;

        if($limit_documents !== 0)
        {
            //fecha de inicio del ciclo de facturacion
            $start_billing_cycle = self::getStartBillingCycleFromSystem();
            
            if($start_billing_cycle){

                //obtener fecha inicio y fin
                $start_end_date = self::getStartEndDateForFilterDocument($start_billing_cycle);
    
                //cantidad de documentos emitidos en el rango de fechas obtenido desde el ciclo de facturacion
                $quantity_documents = Document::whereBetween('date_of_issue', [ $start_end_date['start_date'], $start_end_date['end_date'] ])->count();
    
                if($quantity_documents > $limit_documents)
                {
                    return [
                        'success' => true,
                        'message' => 'Ha superado el límite permitido para la emisión de comprobantes'
                    ];
                }

            }

        }

        return [
            'success' => false,
            'message' => ''
        ];

    }


    /**
     * 
     * Obtener fecha de inicio y fin para filtrar documentos en base 
     * a la fecha de inicio del ciclo de facturacion (planes) del cliente
     *
     * Usado en: 
     * App\Http\Controllers\System\ClientController
     * 
     * @param  $start_billing_cycle
     * @return array
     */
    public static function getStartEndDateForFilterDocument($start_billing_cycle)
    { 
        
        $day_start_billing = date_format($start_billing_cycle, 'j');
        $day_now = (int) date('j');
        $end = Carbon::parse(date('Y-m-d'));
        // $day_now = 6;
        // $end = Carbon::parse(date('2022-01-06'));

        if ($day_now <= $day_start_billing) {

            $init = Carbon::parse(date('Y') . '-' . ((int)date('n') - 1) . '-' . $day_start_billing);

        } else {

            $init = Carbon::parse(date('Y') . '-' . ((int)date('n')) . '-' . $day_start_billing);
            
        }

        return [
            'start_date' => $init,
            'end_date' => $end,
        ];

    }
 
}
