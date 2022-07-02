<?php

    namespace App\Models\Tenant;


    use Illuminate\Database\Eloquent\SoftDeletes;


    class Order extends ModelTenant
    {
        use SoftDeletes;

        protected $fillable = [
            'external_id',
            'customer',
            'shipping_address',
            'items',
            'total',
            'reference_payment',
            'document_external_id',
            'number_document',
            'status_order_id',
            'purchase',
            'apply_restaurant'
        ];

        protected $casts = [
            'customer' => 'object',
            'items' => 'object',
            'purchase' => 'object'
        ];

        public function status_order()
        {
            return $this->belongsTo(StatusOrder::class);
        }

        public function sale_note()
        {
            return $this->hasOne(SaleNote::class);
        }

        /**
         * Retorna un standar de nomenclatura para el modelo
         *
         * @return array
         */
        public function getCollectionData()
        {
            $data = [
                'id' => $this->id,
                'external_id' => $this->external_id,
                'number_document' => $this->number_document,
                'order_id' => str_pad($this->id, 6, "0", STR_PAD_LEFT),
                'customer' => $this->customer->apellidos_y_nombres_o_razon_social,
                'customer_email' => $this->customer->correo_electronico,
                'customer_telefono' => $this->customer->telefono,
                'customer_direccion' => $this->customer->direccion,
                'items' => $this->items,
                'total' => $this->total,
                'reference_payment' => strtoupper($this->reference_payment),
                'document_external_id' => $this->document_external_id,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'status_order_id' => $this->status_order_id,
                'purchase' => $this->purchase,
                'status_order_description' => $this->status_order->description ?? null
            ];

            return $data;
        }
    }
