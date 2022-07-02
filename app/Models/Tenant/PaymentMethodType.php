<?php

    namespace App\Models\Tenant;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection as CollectionAlias;
    use Modules\Finance\Models\IncomePayment;
    use Modules\Pos\Models\CashTransaction;
    use Modules\Sale\Models\ContractPayment;
    use Modules\Sale\Models\QuotationPayment;
    use Modules\Sale\Models\TechnicalServicePayment;

    /**
     * App\Models\Tenant\PaymentMethodType
     *
     * @property int                                                       $id
     * @property string                                                    $description
     * @property bool                                                      $has_card
     * @property-read CollectionAlias|CashTransaction[]                    $cash_transactions
     * @property-read int|null                                             $cash_transactions_count
     * @property-read CollectionAlias|ContractPayment[]                    $contract_payments
     * @property-read int|null                                             $contract_payments_count
     * @property-read CollectionAlias|\App\Models\Tenant\DocumentPayment[] $document_payments
     * @property-read int|null                                             $document_payments_count
     * @property-read CollectionAlias|\App\Models\Tenant\DocumentFee[]     $fee
     * @property-read int|null                                             $fee_count
     * @property-read CollectionAlias|IncomePayment[]                      $income_payments
     * @property-read int|null                                             $income_payments_count
     * @property-read CollectionAlias|\App\Models\Tenant\PurchasePayment[] $purchase_payments
     * @property-read int|null                                             $purchase_payments_count
     * @property-read CollectionAlias|QuotationPayment[]                   $quotation_payments
     * @property-read int|null                                             $quotation_payments_count
     * @property-read CollectionAlias|\App\Models\Tenant\SaleNotePayment[] $sale_note_payments
     * @property-read int|null                                             $sale_note_payments_count
     * @property-read CollectionAlias|TechnicalServicePayment[]            $technical_service_payments
     * @property-read int|null                                             $technical_service_payments_count
     * @method static Builder|PaymentMethodType credit()
     * @method static Builder|PaymentMethodType excludeMethodTypes($exclude_method_types_id = [])
     * @method static Builder|PaymentMethodType newModelQuery()
     * @method static Builder|PaymentMethodType newQuery()
     * @method static Builder|PaymentMethodType nonCredit()
     * @method static Builder|PaymentMethodType query()
     * @method static Builder|PaymentMethodType whereDescription($value)
     * @method static Builder|PaymentMethodType whereFilterPayments($params)
     * @method static Builder|PaymentMethodType whereHasCard($value)
     * @method static Builder|PaymentMethodType whereId($value)
     * @mixin \App\Models\Tenant\ModelTenant
     * @mixin \Eloquent
     */
    class PaymentMethodType extends ModelTenant
    {
        public $incrementing = false;
        public $timestamps = false;

        protected $exclude_method_types = [
            //'01', // Efectivo
            //'02', // Tarjeta de crédito
            //'03', // Tarjeta de débito
            //'04', // Transferencia
            //'05', // Factura a 30 días
            //'06', // Tarjeta crédito visa
            //'07', // Contado contraentrega
            '08', // A 30 días
            '09', // Crédito
            // '10', // Contado
        ];
        protected $fillable = [
            'id',
            'description',
            'has_card',
            'charge',
            'number_days',
            'is_credit',
            'is_cash',
        ];

        public const CASH_PAYMENT_ID = '01';

        /**
         * Devuelve los metodos de pago como standandar. Se pueden excluir elementos por $exclude_method_types_id
         *
         * //'01', // Efectivo
         * //'02', // Tarjeta de crédito
         * //'03', // Tarjeta de débito
         * //'04', // Transferencia
         * //'05', // Factura a 30 días
         * //'06', // Tarjeta crédito visa
         * //'07', // Contado contraentrega
         * '08', // A 30 días
         * '09', // Crédito
         * // '10', // Contado
         *
         * @param array $exclude_method_types_id Id de metodos a excluir
         *
         * @return CollectionAlias
         */
        public static function getPaymentMethodTypes($exclude_method_types_id = [])
        {
            $exclude_method_types_id = array_merge(['08', '09'], $exclude_method_types_id);
            return self::whereNotIn('id', $exclude_method_types_id)
                ->get()
                ->transform(function ($row) {
                    $row->id = (string)$row->id;
                    $row->number_days = (int)$row->number_days;
                    $row->has_card = (bool)$row->has_card;
                    $row->is_credit = (bool)$row->is_credit;
                    $row->has_card = (bool)$row->has_card;
                    $row->is_cash = (bool)$row->is_cash;
                    $row->charge = (float)$row->charge;
                    $row->description = (string)$row->description;
                    return $row;
                });
        }

        /**
         * @return mixed
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @param mixed $description
         *
         * @return PaymentMethodType
         */
        public function setDescription($description)
        {
            $this->description = $description;
            return $this;
        }

        /**
         * @return bool
         */
        public function isIsCash()
        {
            return (bool)$this->is_cash;
        }

        /**
         * @param int|bool $is_cash
         *
         * @return PaymentMethodType
         */
        public function setIsCash($is_cash = 0)
        {
            $this->is_cash = (bool)$is_cash;
            return $this;
        }

        /**
         * @return bool
         */
        public function isIsCredit()
        {
            return (bool)$this->is_credit;
        }

        /**
         * @param int|bool $is_cash
         *
         * @return PaymentMethodType
         */
        public function setIsCredit($is_credit = 0)
        {
            $this->is_credit = (bool)$is_credit;
            return $this;
        }

        /**
         * @param $query
         *
         * @return Builder
         */
        public function scopeNonCredit($query)
        {
            return $query->where('is_credit', 0);
        }

        /**
         * @param $query
         *
         * @return Builder
         */
        public function scopeCredit($query)
        {
            return $query->where('is_credit', 1);
        }

        /**
         * Devuelve los metodos de pago como standandar. Se pueden excluir elementos por $exclude_method_types_id
         *
         * @param       $query
         * @param array $exclude_method_types_id
         *
         * @return Builder
         */
        public function scopeExcludeMethodTypes($query, $exclude_method_types_id = [])
        {
            $exclude_method_types_id = array_merge($this->exclude_method_types, $exclude_method_types_id);
            return $query->whereNotIn('id', $exclude_method_types_id);
        }

        public function document_payments()
        {
            return $this->hasMany(DocumentPayment::class, 'payment_method_type_id');
        }

        public function sale_note_payments()
        {
            return $this->hasMany(SaleNotePayment::class, 'payment_method_type_id');
        }

        public function purchase_payments()
        {
            return $this->hasMany(PurchasePayment::class, 'payment_method_type_id');
        }

        public function quotation_payments()
        {
            return $this->hasMany(QuotationPayment::class, 'payment_method_type_id');
        }

        public function contract_payments()
        {
            return $this->hasMany(ContractPayment::class, 'payment_method_type_id');
        }

        public function income_payments()
        {
            return $this->hasMany(IncomePayment::class, 'payment_method_type_id');
        }

        public function cash_transactions()
        {
            return $this->hasMany(CashTransaction::class, 'payment_method_type_id');
        }

        public function technical_service_payments()
        {
            return $this->hasMany(TechnicalServicePayment::class, 'payment_method_type_id');
        }


        public function scopeWhereFilterPayments($query, $params)
        {

            return $query->with(['document_payments' => function ($q) use ($params) {
                $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                    ->whereHas('associated_record_payment', function ($p) {
                        $p->whereStateTypeAccepted()->whereTypeUser();
                    });
            },
                'sale_note_payments' => function ($q) use ($params) {
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function ($p) {
                            $p->whereStateTypeAccepted()->whereTypeUser()
                                ->whereNotChanged();
                        });
                },
                'quotation_payments' => function ($q) use ($params) {
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function ($p) {
                            $p->whereStateTypeAccepted()->whereTypeUser()
                                ->whereNotChanged();
                        });
                },
                'contract_payments' => function ($q) use ($params) {
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function ($p) {
                            $p->whereStateTypeAccepted()->whereTypeUser()
                                ->whereNotChanged();
                        });
                },
                'purchase_payments' => function ($q) use ($params) {
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function ($p) {
                            $p->whereStateTypeAccepted()->whereTypeUser();
                        });
                },
                'income_payments' => function ($q) use ($params) {
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function ($p) {
                            $p->whereStateTypeAccepted()->whereTypeUser();
                        });
                },
                'cash_transactions' => function ($q) use ($params) {
                    $q->whereBetween('date', [$params->date_start, $params->date_end]);
                },
                'technical_service_payments' => function ($q) use ($params) {
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function ($p) {
                            $p->whereTypeUser();
                        });
                }
            ]);

        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function fee()
        {
            return $this->hasMany(DocumentFee::class);
        }

        /**
         * @param Builder $query
         *
         * @return Builder
         */
        public function scopeNotCredit(Builder $query)
        {
            return $query->where('is_credit', '!=', 1);
        }

        /**
         * @param Builder $query
         *
         * @return Builder
         */
        public function scopeNotCash(Builder $query)
        {
            return $query->where('is_cash', '!=', 1);
        }
    }
