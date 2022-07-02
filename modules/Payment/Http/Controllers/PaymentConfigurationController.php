<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payment\Http\Resources\PaymentConfigurationResource;
use Modules\Payment\Models\PaymentConfiguration;
use Modules\Payment\Http\Requests\PaymentConfigurationRequest;
use Illuminate\Http\Request;
use Modules\Finance\Helpers\UploadFileHelper;


class PaymentConfigurationController extends Controller
{

    /**
     * @return PaymentConfigurationResource
     */
    public function record()
    {
        return new PaymentConfigurationResource(PaymentConfiguration::firstOrFail());
    }


    /**
     * @return array
     */
    public function recordPermissions()
    {
        return [
            'data' => PaymentConfiguration::getPaymentPermissions()
        ];
    }


    /**
     * Actualizar configuracion
     *
     * @param  PaymentConfigurationRequest $request
     * @return array
     */
    public function store(PaymentConfigurationRequest $request)
    {

        $type = $request->type;
        $record = PaymentConfiguration::firstOrFail();

        switch ($type)
        {
            case '01': //yape
                $this->setDataYape($record, $request);
                break;
            case '02': //m pago
                $this->setDataMP($record, $request);
                break;
        }

        $record->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }


    /**
     *
     * @param  PaymentConfiguration $record
     * @param  PaymentConfigurationRequest $request
     * @return void
     */
    public function setDataMP(PaymentConfiguration &$record, $request)
    {
        $record->enabled_mp = $request->enabled_mp;
        $record->public_key_mp = $request->public_key_mp;

        if($request->access_token_mp)
        {
            $record->access_token_mp = $request->access_token_mp;
        }
    }


    /**
     *
     * @param  PaymentConfiguration $record
     * @param  PaymentConfigurationRequest $request
     * @return void
     */
    public function setDataYape(PaymentConfiguration &$record, $request)
    {
        $record->enabled_yape = $request->enabled_yape;
        $record->name_yape = $request->name_yape;
        $record->telephone_yape = $request->telephone_yape;

        if($request->qrcode_yape && $request->temp_path_yape)
        {
            $filename = UploadFileHelper::uploadFileFromTempFile('payment_configurations', $request->qrcode_yape, $request->temp_path_yape, $record->id, 'qr_yape');
            $record->qrcode_yape = $filename;
        }
    }


    /**
     * Cargar qr yape
     *
     * @param  Request $request
     * @return array
     */
    public function uploadQrcodeYape(Request $request)
    {

        $validate_upload = UploadFileHelper::validateUploadFile($request, 'file', 'jpg,jpeg,png,svg');
        if(!$validate_upload['success']) return $validate_upload;

        if ($request->hasFile('file'))
        {
            $new_request = [
                'file' => $request->file('file'),
                'type' => $request->input('type'),
            ];

            return UploadFileHelper::getTempFile($new_request);
        }

        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }


}