<?php

namespace App\Models\Tenant;

use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\RetentionType;
use Illuminate\Support\Facades\DB;

/**
 * Class Retention
 *
 * @package App\Models\Tenant
 * @mixin ModelTenant
 */
class Retention extends ModelTenant
{
    protected $with = ['user', 'soap_type', 'state_type', 'document_type', 'retention_type', 'currency_type', 'documents'];

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'supplier_id',
        'supplier',
        'retention_type_id',
        'observations',
        'currency_type_id',
        'total_retention',
        'total',

        'legends',

        'filename',
        'hash',

        'has_xml',
        'has_pdf',
        'has_cdr',
        'soap_shipping_response',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getSupplierAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setSupplierAttribute($value)
    {
        $this->attributes['supplier'] = (is_null($value))?null:json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function retention_type()
    {
        return $this->belongsTo(RetentionType::class, 'retention_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(RetentionDocument::class);
    }

    /**
     * @return string
     */
    public function getNumberFullAttribute()
    {
        return $this->series.'-'.$this->number;
    }

    /**
     * @return string
     */
    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'retention', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'retention', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'retention', 'type' => 'cdr', 'external_id' => $this->external_id]);
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
            return $facturalo->loadDocument($model->id, 'retention');
        });
    }

    /**
     * @return array
     */
    public function  getCollectionData(){
        $has_cdr = false;

        if (in_array($this->state_type_id, ['05', '07', '09'])) {
            $has_cdr = true;
        }

        return [
            'id' => $this->id,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'number' => $this->number_full,
            'supplier_name' => $this->supplier->name,
            'supplier_number' => $this->supplier->identity_document_type->description.' '.$this->supplier->number,
            'state_type_id' => $this->state_type_id,
            'state_type_description' => $this->state_type->description,
            'total_retention' => $this->total_retention,
            'total' => $this->total,
            'has_xml' => $this->has_xml,
            'has_pdf' => $this->has_pdf,
            'has_cdr' => $has_cdr,
            'download_external_xml' => $this->download_external_xml,
            'download_external_pdf' => $this->download_external_pdf,
            'download_external_cdr' => $this->download_external_cdr,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
