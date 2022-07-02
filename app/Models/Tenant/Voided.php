<?php

namespace App\Models\Tenant;

use App\CoreFacturalo\Facturalo;
use Illuminate\Support\Facades\DB;

/**
 * Class Voided
 *
 * @package App\Models\Tenant
 * @mixin ModelTenant
 */
class Voided extends ModelTenant
{
    protected $table = 'voided';
    protected $with = ['user', 'soap_type', 'state_type', 'documents'];

    protected $fillable = [
        'user_id',
        'external_id',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'date_of_issue',
        'date_of_reference',
        'identifier',
        'filename',
        'ticket',
        'has_ticket',
        'has_cdr',
        'soap_shipping_response',
        
        'send_to_pse',
        'response_signature_pse',
        'response_send_cdr_pse',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_reference' => 'date',
        'send_to_pse' => 'bool',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(VoidedDocument::class);
    }

    /**
     * @return string
     */
    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'voided', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'voided', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'voided', 'type' => 'cdr', 'external_id' => $this->external_id]);
    }

    public function getSoapShippingResponseAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setSoapShippingResponseAttribute($value)
    {
        $this->attributes['soap_shipping_response'] = (is_null($value))?null:json_encode($value);
    }

    /**
     * Devuelve la clase Facturalo con los elementos cargados
     *
     * @return \App\CoreFacturalo\Facturalo
     */
    public function getFacturalo(){

        $model = $this;
        return DB::connection('tenant')->transaction(function () use ($model) {
            $facturalo = new Facturalo();
            return $facturalo->loadDocument($model->id, 'voided');
        });
    }

    /**
     * Obtener tipo de documento válido para enviar el xml a firmar al pse
     *
     * Usado en:
     * App\CoreFacturalo\Services\Helpers\SendDocumentPse
     * 
     * @return string
     */
    public function getDocumentTypeForPse()
    {
        return 'ANUL';
    }

    public function getResponseSendCdrPseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setResponseSendCdrPseAttribute($value)
    {
        $this->attributes['response_send_cdr_pse'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getResponseSignaturePseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setResponseSignaturePseAttribute($value)
    {
        $this->attributes['response_signature_pse'] = (is_null($value)) ? null : json_encode($value);
    }

}
