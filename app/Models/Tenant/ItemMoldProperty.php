<?php



    namespace App\Models\Tenant;

    use App\Models\Tenant\Catalogs\CatItemMoldProperty;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class ItemMoldProperty
     *
     * @property int         $id
     * @property int         $item_id
     * @property int         $cat_item_mold_properties_id
     * @property bool|true   $active
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Collection|ItemMovementRelExtra[] $item_movement_rel_extras
     * @method static \Illuminate\Database\Eloquent\Builder|ItemMoldProperty ByItem()
     * @package App\Models
     */
    class ItemMoldProperty extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'item_id' => 'int',
            'cat_item_mold_properties_id' => 'int',
            'active' => 'bool'
        ];

        protected $fillable = [
            'item_id',
            'cat_item_mold_properties_id',
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
         * @return ItemMoldProperty
         */
        public function setItemId(int $item_id): ItemMoldProperty
        {
            $this->item_id = $item_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getCatItemMoldPropertiesId(): int
        {
            return $this->cat_item_mold_properties_id;
        }

        /**
         * @param int $cat_item_mold_properties_id
         *
         * @return ItemMoldProperty
         */
        public function setCatItemMoldPropertiesId(int $cat_item_mold_properties_id): ItemMoldProperty
        {
            $this->cat_item_mold_properties_id = $cat_item_mold_properties_id;
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
         * @return ItemMoldProperty
         */
        public function setActive(bool $active): ItemMoldProperty
        {
            $this->active = $active;
            return $this;
        }


        /**
         * @return CatItemMoldProperty
         */
        public function getCatItemMoldProperties(): CatItemMoldProperty
        {
            $e = CatItemMoldProperty::find($this->cat_item_mold_properties_id);
            if (empty($e)) $e = new CatItemMoldProperty();
            $this->cat_item_mold_properties = $e;
            $this->cat_item_mold_properties_id = $e->id;
            return $this->cat_item_mold_properties;
        }

        /**
         * @param CatItemMoldProperty $cat_item_mold_properties
         *
         * @return ItemMoldProperty
         */
        public function setCatItemMoldProperties(CatItemMoldProperty $cat_item_mold_properties): ItemMoldProperty
        {
            $this->cat_item_mold_properties = $cat_item_mold_properties;
            $this->cat_item_mold_properties_id = $cat_item_mold_properties->id;
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
