<?php

    namespace Modules\Inventory\Models;

    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class InventoriesTransfer
     *
     * @property int                                                   $id
     * @property int                                                   $user_id
     * @property string|null                                           $description
     * @property int                                                   $warehouse_id
     * @property int                                                   $warehouse_destination_id
     * @property float                                                 $quantity
     * @property Carbon|null                                           $created_at
     * @property Carbon|null                                           $updated_at
     *
     * @property Warehouse                                             $warehouse_destination
     * @property User                                             $user
     * @property Warehouse                                             $warehouse
     * @property Collection|Inventory[]                                $inventories
     *
     * @package Modules\Inventory\Models
     * @mixin ModelTenant
     * @property-read int|null                                         $inventories_count
     * @property-read Collection|\Modules\Inventory\Models\Inventory[] $inventory
     * @property-read int|null                                         $inventory_count
     * @method static Builder|InventoryTransfer newModelQuery()
     * @method static Builder|InventoryTransfer newQuery()
     * @method static Builder|InventoryTransfer query()
     */
    class InventoryTransfer extends ModelTenant
    {


        protected $table = 'inventories_transfer';

        use UsesTenantConnection;

        protected $fillable = [
            'description',
            'warehouse_id',
            'warehouse_destination_id',
            'user_id',
            'quantity',

        ];
        protected $casts = [
            'warehouse_id' => 'int',
            'warehouse_destination_id' => 'int',
            'user_id' => 'int',
            'quantity' => 'float'
        ];

        /**
         * The "booting" method of the model.
         *
         * @return void
         */
        protected static function boot()
        {
            parent::boot();
            static::creating(function (self $model) {
                $model->user_id = 0;
                if (auth() and auth()->user() and auth()->user()->id) {
                    $model->user_id = auth()->user()->id;
                }

            });
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function inventories()
        {
            return $this->hasMany(Inventory::class, 'inventories_transfer_id');
        }


        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function warehouse()
        {
            return $this->belongsTo(Warehouse::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function warehouse_destination()
        {
            return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function inventory()
        {
            return $this->hasMany(Inventory::class, 'inventories_transfer_id');
        }


        public function getPdfData(){

            $data = [];
            $data['serie'] = "NT";
            $data['number'] = $this->id;
            $data['document_type'] = "NOTA DE TRASLADO";
            $data['motivo'] = $this->description;
            $data['created_at'] = $this->created_at;
            $data['quantity'] = $this->quantity;
            $data['warehouse_from'] = $this->warehouse;
            $data['warehouse_to'] = $this->warehouse_destination;
            $data['user'] = $this->user;
            $data['inventories'] = $this->inventories;
            $data['configuration'] = Configuration::first();
            $data['company'] = Company::active();

            return $data;
        }

    }
