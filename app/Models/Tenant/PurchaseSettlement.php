<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\{
    CurrencyType,
    DocumentType
};


class PurchaseSettlement extends ModelTenant
{

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'operation_type_id',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'supplier_id',
        'supplier',
        'operation_data',
        'currency_type_id',
        'exchange_rate_sale',
        'total_prepayment',
        'total_taxed',
        'total_unaffected',
        'total_exonerated',
        'total_igv',
        'total_taxes',
        'total_value',
        'total',
        'subtotal',
        
        'legends',
        'prepayments',
        'related',
        'observation',

        'filename',
        'hash',
        'has_xml',
        'has_pdf',
        'has_cdr',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function getOperationDataAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setOperationDataAttribute($value)
    {
        $this->attributes['operation_data'] = (is_null($value))?null:json_encode($value);
    }

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
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
 
    public function getPrepaymentsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPrepaymentsAttribute($value)
    {
        $this->attributes['prepayments'] = (is_null($value))?null:json_encode($value);
    }

    public function getRelatedAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setRelatedAttribute($value)
    {
        $this->attributes['related'] = (is_null($value))?null:json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }
    
    public function person() {
        return $this->belongsTo(Person::class, 'supplier_id');
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseSettlementItem::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->series.'-'.$this->number;
    }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }
    
    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'purchaseSettlement', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'purchaseSettlement', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'purchaseSettlement', 'type' => 'cdr', 'external_id' => $this->external_id]);
    }

    public function getRowResource()
    {
        
        $has_xml = true;
        $has_pdf = true;
        $has_cdr = false; 

        if ($this->state_type_id === '05') {
            $has_cdr = true;
        }

        return [
            'id' => $this->id,
            'soap_type_id' => $this->soap_type_id,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'number_full' => $this->number_full,
            'supplier_name' => $this->supplier->name,
            'supplier_number' => $this->supplier->identity_document_type->description.' '.$this->supplier->number,
            'total_unaffected' => $this->total_unaffected,
            'total_exonerated' => $this->total_exonerated,
            'total_taxed' => $this->total_taxed,
            'total_igv' => $this->total_igv,
            'total' => $this->total,
            'subtotal' => $this->subtotal,
            'state_type_id' => $this->state_type_id,
            'state_type_description' => $this->state_type->description,
            'has_xml' => $has_xml,
            'has_pdf' => $has_pdf,
            'has_cdr' => $has_cdr,
            'download_external_xml' => $this->download_external_xml,
            'download_external_pdf' => $this->download_external_pdf,
            'download_external_cdr' => $this->download_external_cdr,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }


}