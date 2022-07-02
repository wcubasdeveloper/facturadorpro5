<?php
namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Controller; 
use Exception;
use Illuminate\Http\Request; 
use Modules\Purchase\Imports\PurchaseSeriesImport;
use Maatwebsite\Excel\Excel;

class PurchaseController extends Controller
{
    
    public function importSeries(Request $request)
    {

        if ($request->hasFile('file')) {

            try {

                $import = new PurchaseSeriesImport();
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();

                return [
                    'success' => true,
                    'message' =>  __('app.actions.upload.success'),
                    'data' => $data
                ];

            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

}
