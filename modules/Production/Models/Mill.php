<?php


    namespace Modules\Production\Models;


    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use App\Models\Tenant\SoapType;


    /**
     * Class Mill
     *
     * @property string|null               $name
     * @property string|null               $comment
     * @property int               $id
     * @property Carbon|null       $date_start
     * @property Carbon|null       $time_start
     * @property Carbon|null       $date_end
     * @property Carbon|null       $time_end
     * @property int|null          $user_id
     * @property Carbon|null       $created_at
     * @property Carbon|null       $updated_at
     * @property User|null         $user
     * @property Collection|Item[]   $items
     * @property string|null     $mill_name
     * @property string|null     $lot_code
     * @package Modules\Production\Models
     * @mixin ModelTenant
     *
     */
    class Mill extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'mill';
        protected $perPage = 25;

        protected $casts = [
            'user_id' => 'int',
        ];


        protected $fillable = [
            'name',
            'date_start',
            'time_start',
            'date_end',
            'time_end',
            'user_id',
            'comment',
            'mill_name',
            'lot_code',
            'soap_type_id',
        ];

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
        public function soap_type()
        {
            return $this->belongsTo(SoapType::class);
        }
        
        /**
         * @return BelongsToMany
         */
        public function items()
        {
            return $this->belongsToMany(Item::class, 'mill_items')
                ->withPivot('id', 'height_to_mill', 'total_height')
                ->withTimestamps();
        }

        public function getCollectionData(){
            $data = $this->toArray();

            $data['items'] = $this->items;
            $data['mill_items'] = MillItem::where('mill_id', $this->id)->get()->transform(function (MillItem $row) {
                return $row->getCollectionData();
            });
            $data['user'] = (!empty($this->user))?$this->user->name:'';
            $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');
            return $data;
        }

        
        public function relation_mill_items()
        {
            return $this->hasMany(MillItem::class);
        }

    }
