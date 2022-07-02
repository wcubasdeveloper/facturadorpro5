<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Http\Resources\InventoryCollection2;
use Modules\Inventory\Http\Resources\InventoryResource;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Models\ItemWarehouse;
use App\Models\Tenant\DownloadTray;
use Modules\Report\Http\Resources\DownloadTrayCollecion;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;


class DownloadTrayController extends Controller
{
    use StorageDocument;

    public function index()
    {
        return view('report::download-tray.index');
    }

    public function columns()
    {
        return [
            'description' => 'Producto',
        ];
    }

    public function records(Request $request)
    {
        $records = DownloadTray::latest();

        return new DownloadTrayCollecion($records->paginate(config('tenant.items_per_page')));
    }

    public function download($id)
    {
        $record = DownloadTray::find($id);
        return $this->downloadStorage($record->file_name, $record->path);
    }





}
