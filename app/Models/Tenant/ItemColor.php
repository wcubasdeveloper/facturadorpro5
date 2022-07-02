<?php

    namespace App\Models\Tenant;


    use App\Models\Tenant\Catalogs\CatColorsItem;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Class ItemColor
     *
     * @property int         $id
     * @property int         $item_id
     * @property int         $cat_colors_item_id
     * @property bool        $active
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @package App\Models
     * @method static Builder|ItemColor newModelQuery()
     * @method static Builder|ItemColor newQuery()
     * @method static Builder|ItemColor query()
     * @method static \Illuminate\Database\Eloquent\Builder|ItemColor ByItem($item_id)
     * @mixin Eloquent
     */
    class ItemColor extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'item_color';
        protected $perPage = 25;

        protected $casts = [
            'item_id' => 'int',
            'cat_colors_item_id' => 'int',
            'active' => 'bool'
        ];

        protected $fillable = [
            'item_id',
            'cat_colors_item_id',
            'active'
        ];

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
         * @return ItemColor
         */
        public function setItemId(int $item_id): ItemColor
        {
            $this->item_id = $item_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getCatColorsItemId(): int
        {
            return $this->cat_colors_item_id;
        }

        /**
         * @param int $cat_colors_item_id
         *
         * @return ItemColor
         */
        public function setCatColorsItemId(int $cat_colors_item_id): ItemColor
        {
            $this->cat_colors_item_id = $cat_colors_item_id;
            return $this;
        }

        /**
         * @return bool
         */
        public function isActive(): bool
        {
            return $this->active;
        }

        /**
         * @param bool $active
         *
         * @return ItemColor
         */
        public function setActive(bool $active): ItemColor
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
         * @return ItemColor
         */
        public function setItem(Item $item): ItemColor
        {
            $this->item = $item;
            $this->item_id = $item->id;
            return $this;
        }

        /**
         * @param CatColorsItem $color
         *
         * @return ItemColor
         */
        public function setColor(CatColorsItem $color): ItemColor
        {
            $this->color = $color;
            $this->cat_colors_item_id = $color->id;
            return $this;
        }

        /**
         * Devuelve el id del catalogo de color cat_colors_item_id, si se encuentra activo y el nombre del color
         *
         * @return array
         */
        public function TransformDatatoEdit(): array
        {
            $color = $this->getColor();
            return [
                'id' => $this->id,
                'cat_colors_item_id' => $this->cat_colors_item_id,
                'color_name' => $color->getName(),
                'active' => (bool)$this->active,
            ];
        }

        /**
         * @return CatColorsItem
         */
        public function getColor(): CatColorsItem
        {
            $e = CatColorsItem::find($this->cat_colors_item_id);
            if (empty($e)) $e = new CatColorsItem();
            $this->color = $e;
            $this->cat_colors_item_id = $e->id;
            return $this->color;
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
