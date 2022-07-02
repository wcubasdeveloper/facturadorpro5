<?php

    namespace App\Models\Tenant;

    use App\CoreFacturalo\Facturalo;
    use App\Http\Controllers\Tenant\DownloadController;
    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Models\Tenant\Catalogs\TransferReasonType;
    use App\Models\Tenant\Catalogs\TransportModeType;
    use App\Models\Tenant\Catalogs\UnitType;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\MorphMany;
    use Illuminate\Support\Facades\DB;
    use Modules\Order\Models\OrderForm;
    use Modules\Inventory\Models\InventoryKardex;
    use Modules\Order\Models\OrderNote;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;
    use App\Models\Tenant\Catalogs\RelatedDocumentType;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;

    /**
 * Class Dispatch
 *
 * @package App\Models\Tenant
 * @mixin ModelTenant
 * @property DocumentType $document_type
 * @property \App\Models\Tenant\Establishment $establishment
 * @property \App\Models\Tenant\Document|null $generate_document
 * @property mixed $customer
 * @property mixed $data_affected_document
 * @property mixed $delivery
 * @property mixed $dispatcher
 * @property string $download_external_cdr
 * @property string $download_external_pdf
 * @property string $download_external_xml
 * @property mixed $driver
 * @property mixed $legends
 * @property string $number_full
 * @property mixed $origin
 * @property mixed $secondary_license_plates
 * @property mixed $soap_shipping_response
 * @property \Illuminate\Database\Eloquent\Collection|InventoryKardex[] $inventory_kardex
 * @property int|null $inventory_kardex_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\DispatchItem[] $items
 * @property int|null $items_count
 * @property OrderForm $order_form
 * @property OrderNote $order_note
 * @property \App\Models\Tenant\Person $person
 * @property \App\Models\Tenant\Document $reference_document
 * @property \App\Models\Tenant\SaleNote $sale_note
 * @property \App\Models\Tenant\SoapType $soap_type
 * @property \App\Models\Tenant\StateType $state_type
 * @property TransferReasonType $transfer_reason_type
 * @property TransportModeType $transport_mode_type
 * @property UnitType $unit_type
 * @property \App\Models\Tenant\User $user
 * @method static Builder|Dispatch newModelQuery()
 * @method static Builder|Dispatch newQuery()
 * @method static Builder|Dispatch query()
 * @method static Builder|Dispatch whereStateTypeAccepted()
 * @method static Builder|Dispatch whereTypeUser()
 * @method static Builder|Dispatch whereValuedKardexFormatSunat($params)
 */
    class Dispatch extends ModelTenant
    {
        protected $with = ['user', 'soap_type', 'state_type', 'document_type', 'unit_type', 'transport_mode_type',
            'transfer_reason_type', 'items', 'reference_document'];

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
            'customer_id',
            'customer',
            'observations',
            'transport_mode_type_id',
            'transfer_reason_type_id',
            'transfer_reason_type',
            'transfer_reason_description',
            'date_of_shipping',
            'transshipment_indicator',
            'port_code',
            'unit_type_id',
            'total_weight',
            'packages_number',
            'container_number',
            'origin',
            'delivery',
            'dispatcher',
            'driver',
            'license_plate',

            'legends',

            'filename',
            'hash',

            'has_xml',
            'has_pdf',
            'has_cdr',

            'reference_document_id',
            'reference_order_note_id',
            'reference_quotation_id',
            'reference_order_note_id',
            'reference_order_form_id',
            'secondary_license_plates',
            'reference_sale_note_id',
            'soap_shipping_response',
            'data_affected_document',
            'related',
            
            'send_to_pse',
            'response_signature_pse',
            'response_send_cdr_pse',
            'order_form_external',

        ];

        protected $casts = [
            'date_of_issue' => 'date',
            'date_of_shipping' => 'date',
            'send_to_pse' => 'bool',
        ];

        public function getEstablishmentAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setEstablishmentAttribute($value)
        {
            $this->attributes['establishment'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getCustomerAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setCustomerAttribute($value)
        {
            $this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getOriginAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setOriginAttribute($value)
        {
            $this->attributes['origin'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getDeliveryAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDeliveryAttribute($value)
        {
            $this->attributes['delivery'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getDispatcherAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDispatcherAttribute($value)
        {
            $this->attributes['dispatcher'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getDriverAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDriverAttribute($value)
        {
            $this->attributes['driver'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getLegendsAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setLegendsAttribute($value)
        {
            $this->attributes['legends'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getSoapShippingResponseAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setSoapShippingResponseAttribute($value)
        {
            $this->attributes['soap_shipping_response'] = (is_null($value)) ? null : json_encode($value);
        }
                
        /**
         * Datos del DAM
         *
         * @param $value
         * @return object
         */
        public function getRelatedAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }
        
        /**
         * Datos del DAM
         *
         * @param $value
         * @return void
         */
        public function setRelatedAttribute($value)
        {
            $this->attributes['related'] = (is_null($value)) ? null : json_encode($value);
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
        public function establishment()
        {
            return $this->belongsTo(Establishment::class);
        }

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
        public function state_type()
        {
            return $this->belongsTo(StateType::class);
        }

        /**
         * @return BelongsTo
         */
        public function document_type()
        {
            return $this->belongsTo(DocumentType::class, 'document_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function reference_document()
        {
            return $this->belongsTo(Document::class, 'reference_document_id');
        }

        /**
         * @return BelongsTo
         */
        public function reference_quotation()
        {
            return $this->belongsTo(Quotation::class, 'reference_quotation_id');
        }

        /**
         * @return BelongsTo
         */
        public function unit_type()
        {
            return $this->belongsTo(UnitType::class, 'unit_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function transport_mode_type()
        {
            return $this->belongsTo(TransportModeType::class, 'transport_mode_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function transfer_reason_type()
        {
            return $this->belongsTo(TransferReasonType::class, 'transfer_reason_type_id');
        }

        /**
         * @return HasMany
         */
        public function items()
        {
            return $this->hasMany(DispatchItem::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasOne
         */
        public function generate_document()
        {
            return $this->hasOne(Document::class);
        }

        /**
         * @return string
         */
        public function getNumberFullAttribute()
        {
            return $this->series . '-' . $this->number;
        }

        /**
         * @return string
         */
        public function getDownloadExternalXmlAttribute()
        {
            return route('tenant.download.external_id', ['model' => 'dispatch', 'type' => 'xml', 'external_id' => $this->external_id]);
        }

        /**
         * @return string
         */
        public function getDownloadExternalPdfAttribute()
        {
            return route('tenant.download.external_id', ['model' => 'dispatch', 'type' => 'pdf', 'external_id' => $this->external_id]);
        }

        /**
         * @return string
         */
        public function getDownloadExternalCdrAttribute()
        {
            return route('tenant.download.external_id', ['model' => 'dispatch', 'type' => 'cdr', 'external_id' => $this->external_id]);
        }

        /**
         * @return BelongsTo
         */
        public function person()
        {
            return $this->belongsTo(Person::class, 'customer_id');
        }

        /**
         * @return BelongsTo
         */
        public function order_form()
        {
            return $this->belongsTo(OrderForm::class, 'reference_order_form_id');
        }

        /**
         * Se usa en la relacion con el inventario kardex en modules/Inventory/Traits/InventoryTrait.php.
         * Tambien se debe tener en cuenta modules/Inventory/Providers/InventoryKardexServiceProvider.php y
         * app/Providers/KardexServiceProvider.php para la correcta gestion de kardex
         *
         * @return MorphMany
         */
        public function inventory_kardex()
        {
            return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
        }

        public function getSecondaryLicensePlatesAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setSecondaryLicensePlatesAttribute($value)
        {
            $this->attributes['secondary_license_plates'] = (is_null($value)) ? null : json_encode($value);
        }

        /**
         * @param \Illuminate\Database\Query\Builder|Builder $query
         *
         * @return \Illuminate\Database\Query\Builder|Builder|null
         */
        public function scopeWhereTypeUser($query)
        {
            $user = auth()->user();
            return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
        }

        /**
         * @return BelongsTo
         */
        public function sale_note()
        {
            return $this->belongsTo(SaleNote::class, 'reference_sale_note_id');
        }

        /**
         * @return BelongsTo
         */
        public function order_note()
        {
            return $this->belongsTo(OrderNote::class, 'reference_order_note_id');
        }

        
        /**
         * 
         * Obtener orden de pedido externa o relacionada de order form
         *
         * @return string
         */
        public function getOrderFormDescription()
        {

            $order_form_description = null;

            if($this->order_form)
            {
                $order_form_description = $this->order_form->number_full;
            }
            else if($this->order_form_external)
            {
                $order_form_description = $this->order_form_external;
            }

            return $order_form_description;
            
        }


        /**
         * Retorna un standar de nomenclatura para el modelo
         *
         * @return array
         */
        public function getCollectionData()
        {

            $has_cdr = false;

            if (in_array($this->state_type_id, ['05', '07'])) {
                $has_cdr = true;
            }

            $documents = [];

            if ($this->generate_document) $documents [] = ['description' => $this->generate_document->number_full];
            if ($this->reference_document) $documents [] = ['description' => $this->reference_document->number_full];
 

            //
            return [
                'id' => $this->id,
                'external_id' => $this->external_id,
                'group_id' => $this->group_id,
                'soap_type_id' => $this->soap_type_id,
                'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
                'number' => $this->number_full,
                'customer_id' => $this->customer_id,
                'customer_name' => $this->customer->name,
                'customer_number' => $this->customer->identity_document_type->description . ' ' . $this->customer->number,
                'user_id' => $this->user_id,
                'user_name' => $this->user->name,
                'date_of_shipping' => $this->date_of_shipping->format('Y-m-d'),
                'state_type_id' => $this->state_type_id,
                'state_type_description' => $this->state_type->description,
                'has_xml' => $this->has_xml,
                'has_pdf' => $this->has_pdf,
                // 'has_cdr' => $this->has_cdr,
                'dispatcher' => $this->dispatcher,
                'type_disparcher' => $this->getTypeDispatcher(),
                'has_cdr' => $has_cdr,
                'download_external_xml' => $this->download_external_xml,
                'download_external_pdf' => $this->download_external_pdf,
                'download_external_cdr' => $this->download_external_cdr,
                'reference_document_id' => $this->reference_document_id,
                'reference_order_note_id' => $this->reference_order_note_id,
                'order_notes' => $this->order_note,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
                'soap_shipping_response' => $this->soap_shipping_response,
                'btn_generate_document' => $this->generate_document || $this->reference_document_id ? false : true,
                'transfer_reason_type' => $this->transfer_reason_type,
                'transfer_reason_description' => $this->transfer_reason_description,
                'documents' => $documents,
                'order_form_description' => $this->getOrderFormDescription(),
            ];

        }


        /**
         * Devuelve la clase Facturalo con los elementos cargados
         *
         * @return Facturalo
         */
        public function getFacturalo()
        {

            $model = $this;
            return DB::connection('tenant')->transaction(function () use ($model) {
                $facturalo = new Facturalo();
                return $facturalo->loadDocument($model->id, 'dispatch');
            });

        }
        public function getTypeDispatcher()
        {

            return IdentityDocumentType::where('id', $this->dispatcher->identity_document_type_id)->get();

        }
        /**
         * @return bool
         */
        public function wasSend()
        {
            $temp = $this->soap_shipping_response;
            if (empty($temp)) {
                return false;
            }
            return $temp->sent;
        }

        public function getDataAffectedDocumentAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDataAffectedDocumentAttribute($value)
        {
            $this->attributes['data_affected_document'] = (is_null($value)) ? null : json_encode($value);
        }

        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereStateTypeAccepted($query)
        {
            return $query->whereIn('state_type_id', ['01', '03', '05', '07', '13']);
        }

        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereValuedKardexFormatSunat($query, $params)
        {
            return $query->whereIn('transfer_reason_type_id', ['01', '02', '04', '13'])
                ->whereStateTypeAccepted()
                ->whereTypeUser()
                ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
        }


        /**
         * Obtiene el pdf basado en la impresion url/print/dispatch/{external_id}
         *
         * @return BinaryFileResponse|null
         */
        public function getPdf()
        {
            return DownloadController::getPdf(self::class, $this->external_id);

        }

                
        /**
         * Retornar descripción del documento relacionado (DAM)
         *
         * @return string|null
         */
        public function getRelatedDocumentTypeDescription()
        {

            if($this->related)
            {
                $related_document = RelatedDocumentType::find($this->related->document_type_id);
                if($related_document) return $related_document->description;
            }

            return null;
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
            return 'GUIA';
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

        
        /**
         * 
         * Retornar registro relacionado
         * 
         * Guia generada desde: Cot, Nv, Ped
         * 
         */
        public function getRelationExternalDocument()
        {
            if(!is_null($this->reference_quotation_id)) return $this->reference_quotation;
            
            if(!is_null($this->reference_sale_note_id)) return $this->sale_note;

            if(!is_null($this->reference_order_note_id)) return $this->order_note;
            
            return null;
        }

        
        /**
         * 
         * Validar si existe relación
         *
         * @param $relation_external_document
         * @return bool
         */
        public function isGeneratedFromExternalDocument($relation_external_document)
        {
            return !is_null($relation_external_document);
        }

    }
