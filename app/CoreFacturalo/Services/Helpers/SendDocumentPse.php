<?php

namespace App\CoreFacturalo\Services\Helpers;
 
use App\Models\System\Configuration as SystemConfiguration;
use App\CoreFacturalo\WS\Zip\ZipFileDecompress;
use Exception;


class SendDocumentPse
{
    
    private $company;
    
    public function __construct($company)
    {
        $this->company = $company; 
    }

    
    /**
     * 
     * Enviar cdr al PSE
     *
     * @param  $cdr_zip
     * @param  $document
     * @return void
     */
    public function sendCdr($cdr_zip, $document)
    {

        $cdr = (new ZipFileDecompress)->decompress($cdr_zip);
        $quantity_files = count($cdr);
        $xml_cdr = ($quantity_files == 1) ? $cdr[0]['content'] : $cdr[$quantity_files - 1]['content'];

        $params = [
            'cliente_id' => $this->company->client_id_pse,
            'nombre_archivo_xml' => $document->filename,
            'nombre_archivo_cdr' => 'R-'.$document->filename,
            'archivo' => base64_encode($xml_cdr),
        ];

        $response = $this->sendRequest($this->company->url_send_cdr_pse, $params);
        
        if(!$response['correcto']) $this->throwException("Documento: {$document->filename} - {$response['mensaje']}");

        $this->updateSendPseDocument($document, null, [
            'success' => $response['correcto'],
            'message' => $response['mensaje'],
        ]);
        
    }

    
    /**
     * 
     * Enviar xml a PSE para agregar firma digital
     *
     * @param  string $xmlUnsigned
     * @param  $document
     * @return string
     */
    public function signXml($xmlUnsigned, $document)
    {

        $params = [
            'cliente_id' => $this->company->client_id_pse,
            'correccion' => false,
            // 'correccion' => true,
            'ruc_emisor' => $this->company->number,
            'tipo_doc_procesar' => $document->getDocumentTypeForPse(),
            'nombre_archivo_xml' => $document->filename,
            'archivo' => base64_encode($xmlUnsigned),
        ];

        $response = $this->sendRequest($this->company->url_signature_pse, $params);
        
        if(!$response['correcto']) $this->throwException("Documento: {$document->filename} - {$response['mensaje']}");

        // obtener xml firmado y guardar rpta ws en bd
        $xmlSigned = base64_decode($response['archivo']);

        $this->updateSendPseDocument($document, [
            'success' => $response['correcto'],
            'message' => $response['mensaje'],
        ]);
        
        return $xmlSigned;
    }

    
    /**
     * 
     * Actualizar campos del documento con la respuesta del PSE
     *
     * @param  $document
     * @param  $response_signature_pse
     * @param  $response_send_cdr_pse
     * @return void
     */
    private function updateSendPseDocument($document, $response_signature_pse, $response_send_cdr_pse = null)
    {

        $document->send_to_pse = true; 

        if($response_signature_pse) $document->response_signature_pse = $response_signature_pse;

        if($response_send_cdr_pse) $document->response_send_cdr_pse = $response_send_cdr_pse; 

        $document->update();

    }

    
    /**
     * 
     * Realizar petición a api PSE
     *
     * @param  string $url
     * @param  array $params
     * @return array
     */
    private function sendRequest($url, $params)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            )
        ));
        
        $response = curl_exec($curl);

        if($response === false) $this->throwException('Error desconocido: '.curl_error($curl));
         
        curl_close($curl);

        return json_decode($response, true);

    }


    public function throwException($message)
    {
        throw new Exception($message);
    }
 
}