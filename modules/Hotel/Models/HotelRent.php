<?php

    namespace Modules\Hotel\Models;

    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class \Modules\Hotel\Models\HotelRent
     *
     * @property int                                  $id
     * @property int                                  $customer_id
     * @property string                               $customer
     * @property string|null                          $notes
     * @property int                                  $towels
     * @property int                                  $hotel_room_id
     * @property int                                  $duration
     * @property int                                  $quantity_persons
     * @property Carbon|null                          $input_date
     * @property string|null                          $input_time
     * @property string                               $payment_status
     * @property Carbon                               $output_date
     * @property string                               $output_time
     * @property int                                  $arrears
     * @property string                               $status
     * @property Carbon|null                          $created_at
     * @property Carbon|null                          $updated_at
     * @property Collection|Item[]                    $items
     * @property Collection|Item[]                    $products
     * @method static Builder|HotelRent newModelQuery()
     * @method static Builder|HotelRent newQuery()
     * @method static Builder|HotelRent query()
     * @method static Builder|HotelRent SearchByDate()
     * @mixin ModelTenant
     * @mixin \Eloquent
     * @property-read int|null                        $items_count
     * @property-read int|null                        $products_count
     * @property-read \Modules\Hotel\Models\HotelRoom $room
     */
    class HotelRent extends ModelTenant
    {
        use UsesTenantConnection;
        protected $table = 'hotel_rents';

        protected $fillable = [
            'customer_id',
            'customer',
            'notes',
            'towels',
            'hotel_room_id',
            'duration',
            'quantity_persons',
            'payment_status',
            'output_date',
            'output_time',
            'input_date',
            'input_time',
            'arrears',
            'status'
        ];

        protected $casts = [
            'customer_id' => 'int',
            'towels' => 'int',
            'hotel_room_id' => 'int',
            'duration' => 'int',
            'quantity_persons' => 'int',
            'arrears' => 'int'
        ];

        public function getCustomerAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setCustomerAttribute($value)
        {
            $this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function room()
        {
            return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function items()
        {
            return $this->hasMany(HotelRentItem::class, 'hotel_rent_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function products()
        {
            return $this->hasMany(HotelRentItem::class, 'hotel_rent_id')->where('type','PRO');
        }


        /**
         * @param Builder $query
         * @param null    $date_start
         * @param null    $date_end
         *
         * @return Builder
         */
        public function scopeSearchByDate(Builder $query, $date_start = null, $date_end = null)
        {

            if ($date_end !== null && $date_start !== null) {
                $query->where([['input_date', '>=', $date_start], ['output_date', '<=', $date_end]]);
            }

            return $query;
        }

    }
