<?php



    namespace App\Models\Tenant;

    use App\Models\Tenant\Catalogs\CatItemProductFamily;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class ItemProductFamily
     *
     * @property int         $id
     * @property int         $item_id
     * @property int         $cat_item_product_family_id
     * @property bool|true   $active
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static Builder|ItemProductFamily ByItem()
     * @property Collection|ItemMovementRelExtra[] $item_movement_rel_extras
     * @package App\Models
     */
    class ItemProductFamily extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'item_product_family';
        protected $perPage = 25;
        protected $casts = [
            'item_id' => 'int',
            'cat_item_product_family_id' => 'int',
            'active' => 'bool'
        ];
        protected $fillable = [
            'item_id',
            'cat_item_product_family_id',
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
         * @return int
         */
        public function getItemId(): int
        {
            return $this->item_id;
        }

        /**
         * @param int $item_id
         *
         * @return ItemProductFamily
         */
        public function setItemId(int $item_id): ItemProductFamily
        {
            $this->item_id = $item_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getCatItemProductFamilyId(): int
        {
            return $this->cat_item_product_family_id;
        }

        /**
         * @param int $cat_item_product_family_id
         *
         * @return ItemProductFamily
         */
        public function setCatItemProductFamilyId(int $cat_item_product_family_id): ItemProductFamily
        {
            $this->cat_item_product_family_id = $cat_item_product_family_id;
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
         * @return ItemProductFamily
         */
        public function setActive(bool $active): ItemProductFamily
        {
            $this->active = $active;
            return $this;
        }

        /**
         * @return CatItemProductFamily
         */
        public function getCatItemProductFamily(): CatItemProductFamily
        {
            $e = CatItemProductFamily::find($this->cat_item_product_family_id);
            if (empty($e)) $e = new CatItemProductFamily();
            $this->cat_item_product_family = $e;
            $this->cat_item_product_family_id = $e->id;

            return $this->cat_item_product_family;
        }

        /**
         * @param CatItemProductFamily $cat_item_product_family
         *
         * @return ItemProductFamily
         */
        public function setCatItemProductFamily(CatItemProductFamily $cat_item_product_family): ItemProductFamily
        {
            $this->cat_item_product_family = $cat_item_product_family;
            $this->cat_item_product_family_id = $cat_item_product_family->id;

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
