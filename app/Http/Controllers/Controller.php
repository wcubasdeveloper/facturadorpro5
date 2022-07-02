<?php

    namespace App\Http\Controllers;

    use App\Models\Tenant\Person;
    use App\Models\Tenant\User;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Illuminate\Foundation\Bus\DispatchesJobs;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller as BaseController;
    use Illuminate\Support\Collection;
    use Log;
    use function Config;
    use Illuminate\Support\Facades\Route;
    use Modules\Report\Models\ReportConfiguration;
    use App\Models\Tenant\Configuration;


    /**
     * Class Controller
     *
     * @package App\Http\Controllers
     * @mixin BaseController
     */
    class Controller extends BaseController
    {
        use AuthorizesRequests;
        use DispatchesJobs;
        use ValidatesRequests;

        /**
         * Devuelve un cliente basado en el id
         *
         * @param int $id
         *
         * @return array
         */
        public function searchClientById($id = 0)
        {

            $customers = Person::whereType('customers')
                ->where('id', $id)
                ->get()
                ->transform(function ($row) {
                    /** @var Person $row */
                    return $row->getCollectionData();
                });

            return compact('customers');
        }

        /**
         * Valida si existe una conexion valida al sitio de destino, se requiere configuracion adicional para guardarlo
         * en el archivo en ese caso se debe revisar la funcion ExtraLog
         *
         * @param string $host
         * @param int    $wait_seconds
         * @param false  $extra_log
         * @param false  $print
         */
        public function pingSite($host = 'demo.facturalo.pro', $wait_seconds = 1, $extra_log = false, $print = false)
        {

            $ports = [
                'http' => 80,
                'https' => 443,
            ];
            $string = '';
            foreach ($ports as $key => $port) {
                $fp = @fsockopen($host, $port, $errCode, $errStr, $wait_seconds);
                $string .= "<br>Ping $host:$port ($key) ==> ";
                if ($fp) {
                    $string .= 'SUCCESS';
                    fclose($fp);
                } else {
                    $string .= "ERROR: $errCode - $errStr";
                }
                $string .= PHP_EOL;
            }
            self::ExtraLog(__FILE__ . "::" . __LINE__ . " \n Validando Host $host  \n>>>>\n$string", $extra_log, $print);
        }

        /**
         * Guarda un log en facturalo si la variable EXTRA_LOG es verdadera en el archivo .env

         * @param string $string
         * @param false  $extra_log
         * @param false  $print
         */
        public static function ExtraLog($string = '', $extra_log = false, $print = false)
        {
            if ($extra_log == false) {
                $extra_log = Config('extra.extra_log');
            }
            if ($extra_log === true) {
                $printData = "\n**************************************DEBUG SE ENCUENTRA ACTIVADO**********************************************************************************\n" .
                    $string .
                    "\n**************************************DEBUG SE ENCUENTRA ACTIVADO**********************************************************************************\n";
                Log::channel('facturalo')->debug($printData);
                if ($print === true) {
                    echo $printData;
                }
            }
        }

        /**
         * @param $id
         *
         * @return array
         */
        public function searchItemById($id)
        {
            $items = SearchItemController::getItemsToDocuments(null, $id);

            return compact('items');

        }

        /**
         * @param Request $request
         *
         * @return array
         */
        public function searchItems(Request $request)
        {
            $items = SearchItemController::getItemsToDocuments($request);

            return compact('items');
        }

        /**
         * @return User[]|\Illuminate\Database\Eloquent\Collection|Collection
         */
        public function getSellers()
        {

            return User::whereIn('type', ['seller', 'admin'])->orderBy('name')->get()->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'type' => $row->type,
                ];
            });
        }

        /**
         * Metodo general para buscar clientes
         *
         * @param Request $request
         *
         * @return array
         */
        public function searchCustomers(Request $request)
        {

            //true de boletas en env esta en true filtra a los con dni   , false a todos
            $identity_document_type_id = [1, 4, 6, 7, 0];
            if (in_array($request->operation_type_id, ['0101', '1001', '1004'])) {
                $identity_document_type_id = config('tenant.document_type_03_filter') ? [1] : [1, 4, 6, 7, 0];
                if ($request->document_type_id == '01') {
                    $identity_document_type_id = [6];
                }
            }
            //dispatcher
            if ($request->has('searchBy')) {
                if ($request->searchBy == 'dispatches') {
                    $identity_document_type_id = ['6', '4', '1'];
                }
            }
            $customers = Person::where('number', 'like', "%{$request->input}%")
                ->orWhere('name', 'like', "%{$request->input}%")
                ->whereType('customers')->orderBy('name')
                ->whereIn('identity_document_type_id', $identity_document_type_id)
                ->whereIsEnabled()
                ->get()->transform(function ($row) {
                    /** @var  Person $row */
                    return $row->getCollectionData();
                });

            return compact('customers');
        }

        public function getCurlRequest($url = 'e-factura.sunat.gob.pe',$wait_seconds = 120, $extra_log = false, $print = false){


            //  $type = 'GET',
            // CURLOPT_CUSTOMREQUEST => $type,

    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => $wait_seconds,      // timeout on connect
        CURLOPT_TIMEOUT        => $wait_seconds,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
$string = var_export($header,true);

            self::ExtraLog(__FILE__ . "::" . __LINE__ . " \n Validando Host $url  \n>>>>\n$string", $extra_log, $print);


            return $header;
        }

        
        /**
         * 
         * Determinar si aplica conversión a soles en reportes registrados en ReportConfiguration
         * 
         * Usado en:
         * ReportGeneralItemController
         *
         * @param  $request
         * @return bool
         */
        public function applyConversiontoPen($request)
        {
            $report_configuration = ReportConfiguration::whereApplyConversion(Route::current()->getName())->first();

            if($report_configuration) return $report_configuration->convert_pen;

            return false;
        }
        

        /**
         * 
         * Determinar si aplica busqueda avanzada
         * 
         * Usado en:
         * ItemController
         *
         * @return bool
         */
        public function applyAdvancedRecordsSearch()
        {   
            return Configuration::isEnabledAdvancedRecordsSearch();
        }

    }
