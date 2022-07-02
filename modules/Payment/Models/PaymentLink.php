<?php

namespace Modules\Payment\Models;

use Carbon\Carbon;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Tenant\{
    DocumentPayment,
    ModelTenant,
    SoapType,
    User,
};
use Modules\MercadoPago\Models\Transaction;


/**
 * Se elimina el registro al borrar el pago relacionado
 * Usa GlobalPaymentServiceProvider para el evento deleting del modelo
 */
class PaymentLink extends ModelTenant
{

    protected $fillable = [
        'soap_type_id',
        'uuid',
        'user_id',
        'payment_link_type_id',
        'payment_id',
        'payment_type',
        'total',
        'uploaded_filename',
        'query_transaction',
    ];


    protected $casts = [
        'query_transaction' => 'bool',
    ];


    /**
     * @return BelongsTo
     */
    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }
 
    
    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(PaymentLinkType::class, 'payment_link_type_id');
    }


    /**
     * @return MorphTo
     */
    public function payment()
    {
        return $this->morphTo();
    }


    /**
     * @return mixed
     */
    public function doc_payments()
    {
        return $this->belongsTo(DocumentPayment::class, 'payment_id')->wherePaymentType(DocumentPayment::class);
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    /**
     * @return string
     */
    public function getInstanceTypeAttribute()
    {
        $instance_type = [
            DocumentPayment::class => 'document',
        ];

        return $instance_type[$this->payment_type];
    }


    public function getInstanceTypeDescriptionAttribute()
    {

        $description = null;

        switch ($this->instance_type) {
            case 'document':
                $description = 'CPE';
                break;
        }

        return $description;
    }


    public function getDataPersonAttribute()
    {

        $record = $this->payment->associated_record_payment;

        switch ($this->instance_type) {

            case 'document':
                $person['name'] = $record->customer->name;
                $person['number'] = $record->customer->number;
                break;

        }

        return (object)$person;
    }
 
    
    /**
     * @return string
     */
    public function getUserPaymentLinkAttribute()
    {
        return url("pagos/{$this->uuid}/{$this->payment_link_type_id}/{$this->total}");
    }


    /**
     * @return string
     */
    public function getImageUrlUploadedFilenameAttribute()
    {
        return $this->uploaded_filename ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'payment_links'.DIRECTORY_SEPARATOR.$this->uploaded_filename) : null;
    }

    
    /**
     * Usado para mostrar el link de pago al generarlo desde pagos (cpe)
     * 
     * @return array
     */
    public function getRowResource()
    {

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'user_id' => $this->user_id,
            'payment_link_type_id' => $this->payment_link_type_id,
            'payment_id' => $this->payment_id,
            'payment_type' => $this->payment_type,
            'total' => $this->total,
            'uploaded_filename' => $this->uploaded_filename,
            'instance_type' => $this->instance_type,
            'user_payment_link' => $this->user_payment_link,
            'image_url_uploaded_filename' => $this->image_url_uploaded_filename,
            'query_transaction' => $this->query_transaction,
            'transaction' => $this->getShowDataTransactionApproved(),
        ];

    }


    /**
     * Usado para mostrar el link de pago al generarlo desde el listado
     * 
     * @return array
     */
    public function getRowResourceWithoutPayment()
    {

        return [
            'id' => $this->id,
            'payment_link_type_id' => $this->payment_link_type_id,
            'total' => $this->total,
            'without_payment' => true,
        ];

    }


    /**
     * @return array
     */
    public function getRowCollection()
    {

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'payment_link_type_id' => $this->payment_link_type_id,
            'payment_link_type_description' => $this->type->description,
            'total' => $this->total,
            'user_payment_link' => $this->user_payment_link,
            'query_transaction' => $this->query_transaction,
            'has_payment' => $this->has_payment,
            'payment_number_full' => $this->getPaymentNumberFull(),
        ];
    }
    
    
    /**
     * 
     * Obtener descripción del pago asociado
     *
     * @return string
     */
    public function getPaymentNumberFull()
    {
        if($this->has_payment)
        {
            return "PAGO-{$this->payment->id}";
        }

        return null;
    }


    /**
     * 
     * Obtener path del modelo en base al tipo de instancia definida
     *
     * @param  string $instance_type
     * @return string
     */
    public static function getModelByType($instance_type)
    {
        $model = null;

        switch ($instance_type) {
            case 'document':
                $model = DocumentPayment::class;
                break;
        }

        return $model;
    }
    

    /**
     * 
     * Filtros para buscar link de pago en url publica
     *
     * @param $query
     */
    public function scopeWhereFilterPublicData($query, $payment_link_type_id, $uuid)
    {
        return $query->where('payment_link_type_id', $payment_link_type_id)->where('uuid', $uuid);
    }
    

    /**
     * Validar si tiene asociado un pago
     *
     * @return bool
     */
    public function getHasPaymentAttribute()
    {
        return !is_null($this->payment);
    }

    
    /**
     * 
     * Obtener datos de registro origen del pago
     *
     * @param  bool $has_payment
     * @return array|null
     */
    public function getAssociatedRecordPaymentData($has_payment)
    {
        if($has_payment)
        {
            $associated_record_payment = $this->payment->associated_record_payment;

            return [
                'currency_type_id' => $associated_record_payment->currency_type_id,
                'exchange_rate_sale' => $associated_record_payment->exchange_rate_sale,
            ];
        }

        return null;
    }

        
    /**
     * 
     * Obtener datps del link de pago para url publica
     *
     * @return array
     */
    public function getFormPublicData()
    {

        $has_payment = $this->has_payment;

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'payment_link_type_id' => $this->payment_link_type_id,
            'total' => $this->total,
            'has_payment' => $has_payment,
            'associated_record_payment' => $this->getAssociatedRecordPaymentData($has_payment),
        ];

    }
        
    
    /**
     * 
     * Datos para mostrar transacción en vista 
     *
     * @return array
     */
    public function getShowDataTransactionApproved()
    {
        $transaction_state_message =  null;
        $transaction_total =  null;

        if($this->query_transaction)
        {
            $transaction = $this->getTransactionApproved();
            $transaction_state_message =  $transaction->getStateUserMessage();
            $transaction_total =  $transaction->amount;
        }

        return [
            'transaction_state_message' => $transaction_state_message,
            'transaction_total' => $transaction_total,
        ];
    }

    
    /**
     * 
     * Obtener transacción aceptada
     *
     * @param  bool $with_select
     * @return Transaction
     */
    public function getTransactionApproved($with_select = false)
    {
        $transaction = $this->transactions()->where('transaction_state_id', Transaction::TRANSACTION_STATE_APPROVED);

        if($with_select) $transaction->select('id');

        return $transaction->first();
    }


    /**
     * 
     * Validar si el link de pago tiene transaccion aceptada de mercado pago
     *
     * @return bool
     */
    public function isTransactionApproved()
    {
        return !is_null($this->getTransactionApproved(true));
    }


}
