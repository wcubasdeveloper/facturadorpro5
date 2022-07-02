<?php

namespace App\Models\Tenant;

use Modules\Expense\Models\Expense;
use Modules\Expense\Models\ExpensePayment;
use Modules\Sale\Models\TechnicalService;
use Illuminate\Database\Eloquent\Builder;


class CashDocument extends ModelTenant
{
    protected $with = ['document','sale_note'];

    public $timestamps = false;

    protected $fillable = [
        'cash_id',
        'document_id',
        'sale_note_id',

        'technical_service_id',
        // 'expense_id',
        'expense_payment_id',
        'purchase_id',
        'quotation_id',

    ];



    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class);
    }

    public function expense_payment()
    {
        return $this->belongsTo(ExpensePayment::class);
    }

    public function technical_service()
    {
        return $this->belongsTo(TechnicalService::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    // public function expense()
    // {
    //     return $this->belongsTo(Expense::class);
    // }

    
    /**
     * 
     * Filtro para obtener ids de los documentos asociados a caja
     *
     * @param Builder $query
     * @param Cash $cash
     * @return array
     */ 
    public function scopeGetDocumentIdsReport($query, $cash)
    {
        return $query->select('document_id')->whereHas('document')->where('cash_id', $cash->id)->get()->pluck('document_id')->toArray();
    }


    /**
     * 
     * Filtro para obtener ids de las notas de venta asociadas a caja
     *
     * @param Builder $query
     * @param Cash $cash
     * @return array
     */ 
    public function scopeGetSaleNoteIdsReport($query, $cash)
    {
        return $query->select('sale_note_id')->whereHas('sale_note')->where('cash_id', $cash->id)->get()->pluck('sale_note_id')->toArray();
    }


    /**
     * 
     * Filtro para obtener ids de las compras asociadas a caja
     *
     * @param Builder $query
     * @param Cash $cash
     * @return array
     */ 
    public function scopeGetPurchaseIdsReport($query, $cash)
    {
        return $query->select('purchase_id')->whereHas('purchase')->where('cash_id', $cash->id)->get()->pluck('purchase_id')->toArray();
    }

    
    /**
     * 
     * Retornar el modelo asociado dependiendo del registro relacionado
     * 
     */
    public function getDataModelAssociated()
    {
        
        if(!is_null($this->document_id)) return $this->document;

        if(!is_null($this->sale_note_id)) return $this->sale_note;
        
        if(!is_null($this->technical_service_id)) return $this->technical_service;
        
        if(!is_null($this->expense_payment_id)) return $this->expense_payment;
        
        if(!is_null($this->purchase_id)) return $this->purchase;

        if(!is_null($this->quotation_id)) return $this->quotation;

    }

}
