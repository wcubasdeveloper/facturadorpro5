<?php


    namespace Modules\Production\Models;


    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use App\Models\Tenant\SoapType;

    
    /**
     * Class Packaging
     *
     * @property int         $id
     * @property int         $item_id
     * @property int|null    $user_id
     * @property int|null    $establishment_id
     * @property float|null  $quantity
     * @property float|null  $number_packages
     * @property string|null $item
     * @property string|null $observation
     * @property string|null $item_extra_data
     * @property string|null $lot_code
     * @property string|null $packaging_collaborator
     * @property Carbon|null $date_start
     * @property Carbon|null $time_start
     * @property Carbon|null $date_end
     * @property Carbon|null $time_end
     * @property Carbon|null $created_at
     * @property string|null $name
     * @property Carbon|null $updated_at
     * @package  Modules\Production\Models
     * @mixin ModelTenant
     * @mixin Eloquent
     */
    class Packaging extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'packaging';

        protected $casts = [
            'item_id' => 'int',
            'user_id' => 'int',
            'establishment_id' => 'int',
            'quantity' => 'float',
            'number_packages' => 'float'
        ];
        protected $fillable = [
            'name',
            'item_id',
            'user_id',
            'establishment_id',
            'item_extra_data',
            'quantity',
            'number_packages',
            'item',
            'observation',
            'lot_code',
            'date_start',
            'time_start',
            'date_end',
            'time_end',
            'packaging_collaborator',
            'soap_type_id',
        ];

        /**
         * @return BelongsTo
         */
        public function item()
        {
            return $this->belongsTo(Item::class);
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

        public function getCollectionData()
        {
            $data = $this->toArray();
            $data['item'] = $this->item;
            $data['user'] = $this->user->name;
            $data['quantity'] = $this->quantity;
            $data['item_name'] = $this->item->description;
            $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');
            $data['stablishment'] = $this->establishment->description;
            return
                $data;
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getItemExtraDataAttribute($value)
        {
            return (null === $value) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setItemExtraDataAttribute($value)
        {
            $this->attributes['item_extra_data'] = (null === $value) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getItemAttribute($value)
        {
            return (null === $value) ? null : (object) json_decode($value);
        }

        /**
         * @param $value
         */
        public function setItemAttribute($value)
        {
            $this->attributes['item'] = (null === $value) ? null : json_encode($value);
        }
    }
