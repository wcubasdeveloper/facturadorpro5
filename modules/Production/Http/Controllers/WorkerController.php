<?php
namespace Modules\Production\Http\Controllers;

use Modules\Production\Http\Requests\WorkerRequest;
use Modules\Production\Http\Resources\WorkerCollection;
use Modules\Production\Http\Resources\WorkerResource;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Http\Controllers\Controller;
use Modules\Production\Models\Worker;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;


class WorkerController extends Controller
{

    public function index()
    {
        return view('production::workers.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número',
        ];
    }


    public function records(Request $request)
    {
        $records = Worker::where($request->column, 'like', "%{$request->value}%")->orderBy('name');
        return new WorkerCollection($records->paginate(config('tenant.items_per_page')));
    }

 
    public function tables()
    {
        $identity_document_types = IdentityDocumentType::whereActive()->whereIn('id', ['1', '4', '7'])->get();

        return compact('identity_document_types');
    }


    public function record($id)
    {
        return new WorkerResource(Worker::findOrFail($id));
    }


    public function store(WorkerRequest $request)
    {
        $id = $request->input('id');

        $worker = Worker::firstOrNew(['id' => $id]);
        $worker->fill($request->all());
        $worker->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Empleado editado con éxito':'Empleado registrado con éxito',
        ];
    }


    public function destroy($id)
    {
        $worker = Worker::findOrFail($id);
        $worker->delete();

        return [
            'success' => true,
            'message' => 'Empleado eliminado con éxito'
        ];
    }


}
