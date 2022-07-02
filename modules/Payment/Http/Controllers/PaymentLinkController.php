<?php
namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\{
    DocumentPayment,
    Company
};
use Exception, Illuminate\Support\Facades\DB;
use Modules\Payment\Http\Requests\PaymentLinkRequest;
use Carbon\Carbon;
use Modules\Payment\Models\{
    PaymentLink,
    PaymentLinkType,
    PaymentConfiguration,
};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Payment\Mail\PaymentLinkEmail;
use Modules\Finance\Helpers\UploadFileHelper;
use Modules\Payment\Traits\PaymentLinkTrait;
use Modules\Payment\Http\Resources\{
    PaymentLinkCollection,
    PaymentLinkResource,
};


class PaymentLinkController extends Controller
{

    use PaymentLinkTrait;


    public function index()
    {
        return view('payment::payment_links.index');
    }


    public function columns()
    {
        return [
            'uuid' => 'Identificador',
            'total' => 'Total',
        ];
    }


    public function tables()
    {
        $payment_link_types = PaymentLinkType::get();

        return compact('payment_link_types');
    }


    public function records(Request $request)
    {
        $records = PaymentLink::where($request->column, 'like', "%{$request->value}%")->latest();

        return new PaymentLinkCollection($records->paginate(config('tenant.items_per_page')));
    }


    /**
     * Buscar link de pago desde form de pagos
     *
     * @param  int $document_payment_id
     * @param  string $instance_type
     * @param  int $payment_link_type_id
     * @return array
     */
    public function record($document_payment_id, $instance_type, $payment_link_type_id)
    {

        $payment_link = PaymentLink::where('payment_link_type_id', $payment_link_type_id)
                                        ->where('payment_id', $document_payment_id)
                                        ->where('payment_type', PaymentLink::getModelByType($instance_type))
                                        ->first();


        if(is_null($payment_link))
        {
            return [
                'has_payment_link' => false,
                'data' => [],
            ];
        }

        return [
            'has_payment_link' => true,
            'data' => $payment_link->getRowResource(),
        ];

    }


    /**
     * Registrar link de pago desde un pago (cpe)
     *
     * @param  PaymentLinkRequest $request
     * @return array
     */
    public function store(PaymentLinkRequest $request)
    {

        $record = PaymentLink::create([
            'user_id' => auth()->id(),
            'uuid' => Str::uuid()->toString(),
            'soap_type_id' => Company::select('soap_type_id')->firstOrFail()->soap_type_id,
            'payment_link_type_id' => $request->payment_link_type_id,
            'payment_id' => $request->payment_id,
            'payment_type' => PaymentLink::getModelByType($request->instance_type),
            'total' => $request->total,
        ]);

        return [
            'success' => true,
            'message' => 'Link generado con éxito'
        ];

    }


    /**
     *
     * Registrar/Actualizar link de pago desde el listado
     *
     * @param  PaymentLinkRequest $request
     * @return array
     */
    public function storeWithoutPayment(PaymentLinkRequest $request)
    {

        $id = $request->input('id');
        $record = PaymentLink::firstOrNew(['id' => $id]);

        $record->fill([
            'user_id' => auth()->id(),
            'uuid' => Str::uuid()->toString(),
            'soap_type_id' => $record->soap_type_id ?? Company::select('soap_type_id')->firstOrFail()->soap_type_id,
            'payment_link_type_id' => $request->payment_link_type_id,
            'total' => $request->total,
        ]);

        $record->save();

        return [
            'success' => true,
            'message' => $id ? 'Link actualizado con éxito' : 'Link generado con éxito'
        ];

    }


    /**
     *
     * Buscar link de pago desde listado
     *
     * @param  int $id
     * @return PaymentLinkResource
     */
    public function recordWithoutPayment($id)
    {
        return new PaymentLinkResource(PaymentLink::findOrFail($id));
    }


    /**
     *
     * Buscar transacciones
     *
     * @param  int $id
     * @return array
     */
    public function transactions($id)
    {
        $payment_link = PaymentLink::findOrFail($id);

        return $payment_link->transactions->transform(function($row){
                    return $row->getRowResource();
                });
    }


    /**
     *
     * Eliminar link de pago
     *
     * @param  int $id
     * @return array
     */
    public function destroy($id)
    {
        $payment_link = PaymentLink::findOrFail($id);

        if($payment_link->transactions->count() > 0)
        {
            return [
                'success' => false,
                'message' => 'El link de pago tiene transacciones relacionadas'
            ];
        }

        $payment_link->delete();

        return [
            'success' => true,
            'message' => 'Link de pago eliminado con éxito'
        ];
    }


    /**
     *
     * Consultar y validar estado Aceptado de la transacción de mercado pago
     *
     * @param  Request $request
     * @return array
     */
    public function queryTransactionState(Request $request)
    {

        $payment_link = PaymentLink::findOrFail($request->id);

        if($payment_link->isTransactionApproved())
        {
            $payment_link->query_transaction = true;
            $payment_link->update();

            return [
                'success' => true,
                'message' => 'La transacción asociada tiene estado Aceptado.'
            ];
        }

        return [
            'success' => false,
            'message' => $payment_link->transactions->count() == 0 ? 'No se encontraron transacciones asociadas.' : 'La transacción asociada no tiene estado Aceptado.'
        ];

    }


    /**
     * Mostrar formulario público del link de pago
     *
     * @param  string $uuid
     * @param  string $payment_link_type_id
     * @param  float $total
     */
    public function publicPaymentLink($uuid, $payment_link_type_id, $input_total)
    {

        $this->validatePublicParams($payment_link_type_id, $input_total);
        $payment_link = $this->getPublicPaymentLink($payment_link_type_id, $uuid);
        $company = $this->getPublicDataCompany();
        $payment_configuration = PaymentConfiguration::getPublicRowResource();

        $apply_conversion = false;
        $total = $this->getTotal($payment_link, $input_total, $apply_conversion);

        return view('payment::payment_links.public.index', compact('payment_link', 'company', 'payment_configuration', 'total', 'apply_conversion'));

    }


    /**
     * Enviar correo
     *
     * @param  Request $request
     * @return array
     */
    public function email(Request $request)
    {

        $company = $this->getPublicDataCompany();

        Mail::to($request->customer_email)->send(new PaymentLinkEmail($company, $request->user_payment_link));

        return [
            'success' => true,
            'message' => 'El correo fue enviado satisfactoriamente'
        ];

    }


    /**
     * Cargar voucher
     *
     * @param  Request $request
     * @return array
     */
    public function uploadedFile(Request $request)
    {

        $validate_upload = UploadFileHelper::validateUploadFile($request, 'file', 'jpg,jpeg,png,svg');
        if(!$validate_upload['success']) return $validate_upload;

        if ($request->hasFile('file'))
        {

            $payment_link = PaymentLink::findOrFail($request->id);

            $new_request = [
                'file' => $request->file('file'),
                'type' => $request->input('type'),
            ];

            $temp_file = UploadFileHelper::getTempFile($new_request);

            if($temp_file['success'])
            {
                $filename = UploadFileHelper::uploadFileFromTempFile('payment_links', $temp_file['data']['filename'], $temp_file['data']['temp_path'], $payment_link->id);
                $payment_link->uploaded_filename = $filename;
                $payment_link->save();

                return [
                    'success' => true,
                    'message' => 'Archivo cargado correctamente',
                ];

            }
        }

        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

}
