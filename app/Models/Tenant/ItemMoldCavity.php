<?php



    namespace App\Models\Tenant;

    use App\Models\Tenant\Catalogs\CatItemMoldCavity;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\HasOne;

    /**
     * Class ItemMoldCavity
     *
     * @property int         $id
     * @property int         $item_id
     * @property int         $cat_item_mold_cavities_id
     * @property bool|true   $active
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Collection|ItemMovementRelExtra[] $item_movement_rel_extras
     * @method static Builder|ItemMoldCavity ByItem()
     * @package App\Models
     */
    class ItemMoldCavity extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'item_id' => 'int',
            'cat_item_mold_cavities_id' => 'int',
            'active' => 'bool'
        ];

        protected $fillable = [
            'item_id',
            'cat_item_mold_cavities_id',
            'active'
        ];

        /**
         * @return int
         */
        public function getCatItemMoldCavitiesId(): int
        {
            return $this->cat_item_mold_cavities_id;
        }

        /**
         * @param int $cat_item_mold_cavities_id
         *
         * @return ItemMoldCavity
         */
        public function setCatItemMoldCavitiesId(int $cat_item_mold_cavities_id): ItemMoldCavity
        {
            $this->cat_item_mold_cavities_id = $cat_item_mold_cavities_id;
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
         * @return ItemMoldCavity
         */
        public function setItemId(int $item_id): ItemMoldCavity
        {
            $this->item_id = $item_id;
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
         * @return ItemMoldCavity
         */
        public function setActive(bool $active): ItemMoldCavity
        {
            $this->active = $active;
            return $this;
        }


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
         * @return CatItemMoldCavity
         */
        public function getCatItemMoldCavities(): CatItemMoldCavity
        {

            $e = CatItemMoldCavity::find($this->cat_item_mold_cavities_id);
            if (empty($e)) $e = new CatItemMoldCavity();
            $this->cat_item_mold_cavities = $e;
            $this->cat_item_mold_cavities_id = $e->id;

            return $this->cat_item_mold_cavities;
        }

        /**
         * @param CatItemMoldCavity $cat_item_mold_cavities
         *
         * @return $this
         */
        public function setCatItemMoldCavities(CatItemMoldCavity $cat_item_mold_cavities)
        {
            $this->cat_item_mold_cavities = $cat_item_mold_cavities;
            $this->cat_item_mold_cavities_id = $cat_item_mold_cavities->id;

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
