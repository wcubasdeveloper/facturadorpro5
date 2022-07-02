<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Controllers\Tenant\Api\ServiceController as ApiServiceController;
use Modules\ApiPeruDev\Http\Controllers\ServiceController as ApiPeruDevServiceController;

class ServiceController extends Controller
{
    /*
     * $date = fecha
     * si no se obtiene resultado de sunat se consulta apiperu.dev
     * IDEA: pasar el servicio a system para que con una tarea de cron se consulten las apis/servicios disponibles
     *       y que este a su vez actualice cada tenant ya que clientes reportan que un tenant tiene la tasa de cambio
     *       y otros no, de esta manera se regularizaria el valor en todos los tenant y se podria actualizar o mantener
     *       actualizado
     */
    public function exchange($date)
    {
        $res = (new ApiServiceController())->exchangeRateTest($date);
        if($res['sale'] === 0) {
            $res = (new ApiPeruDevServiceController())->exchange($date);
        }

        return $res;
    }
}
