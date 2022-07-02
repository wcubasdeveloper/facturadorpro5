<?php



    namespace App\Models\Tenant;

    use App\Models\Tenant\Catalogs\CatItemUnitBusiness;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class ItemUnitBusiness
     *
     * @property int         $id
     * @property int         $item_id
     * @property int         $cat_item_unit_business_id
     * @property bool|true   $active
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Collection|ItemMovementRelExtra[] $item_movement_rel_extras
     * @method static \Illuminate\Database\Eloquent\Builder|ItemUnitBusiness ByItem()
     * @package App\Models
     */
    class ItemUnitBusiness extends ModelTenant
    {
        use UsesTenantConnection;


        protected $table = 'item_unit_business';
        protected $perPage = 25;
        protected $casts = [
            'item_id' => 'int',
            'cat_item_unit_business_id' => 'int',
            'active' => 'bool'
        ];
        protected $fillable = [
            'item_id',
            'cat_item_unit_business_id',
            'active'
        ];

        /**
         * @return Item
         */
        public function getItem(): Item
        {
            $e = Item::find($this->item_id);
            if (empty($e)) $e = new Item();
            $this->item = $e;
            $this->item_id = $e->id;
            return $this->item;
        }

        /**
         * @param Item $item
         *
         * @return $this
         */
        public function setItem(Item $item)
        {
            $this->item = $item;
            $this->item_id = $item->id;
            return $this;
        }

        /**
         * @return CatItemUnitBusiness
         */
        public function getCatItemUnitBusiness(): CatItemUnitBusiness
        {
            $e = CatItemUnitBusiness::find($this->cat_item_unit_business_id);
            if (empty($e)) $e = new CatItemUnitBusiness();
            $this->cat_item_unit_business = $e;
            $this->cat_item_unit_business_id = $e->id;

            return $this->cat_item_unit_business;
        }

        /**
         * @param CatItemUnitBusiness $cat_item_unit_business
         *
         * @return ItemUnitBusiness
         */
        public function setCatItemUnitBusiness(CatItemUnitBusiness $cat_item_unit_business): ItemUnitBusiness
        {
            $this->cat_item_unit_business = $cat_item_unit_business;
            $this->cat_item_unit_business_id = $cat_item_unit_business->id;
            return $this;
        }

        /**
         * @return int
         */
        public function getItemId(): int
        {
            return $this->item_id;
        }

        /**
         * @param int $item_id
         *
         * @return ItemUnitBusiness
         */
        public function setItemId(int $item_id): ItemUnitBusiness
        {
            $this->item_id = $item_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getCatItemUnitBusinessId(): int
        {
            return $this->cat_item_unit_business_id;
        }

        /**
         * @param int $cat_item_unit_business_id
         *
         * @return ItemUnitBusiness
         */
        public function setCatItemUnitBusinessId(int $cat_item_unit_business_id): ItemUnitBusiness
        {
            $this->cat_item_unit_business_id = $cat_item_unit_business_id;
            return $this;
        }

        /**
         * @return bool|true
         */
        public function getActive(): bool
        {
            return $this->active;
        }

        /**
         * @param bool|true $active
         *
         * @return ItemUnitBusiness
         */
        public function setActive(bool $active): ItemUnitBusiness
        {
            $this->active = $active;
            return $this;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function item_movement_rel_extra()
        {
            return $this->hasMany(ItemMovementRelExtra::class);
        }

         /**
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param int $item_id
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeByItem($query,$item_id){
            return $query->where('item_id',$item_id);
        }
    }
