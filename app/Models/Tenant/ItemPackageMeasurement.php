<?php



    namespace App\Models\Tenant;

    use App\Models\Tenant\Catalogs\CatItemPackageMeasurement;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class ItemPackageMeasurement
     *
     * @property int         $id
     * @property int         $item_id
     * @property int         $cat_item_package_measurements_id
     * @property bool|true   $active
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Collection|ItemMovementRelExtra[] $item_movement_rel_extras
     * @package App\Models
     * @method static Builder|ItemPackageMeasurement ByItem()
     */
    class ItemPackageMeasurement extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;
        protected $casts = [
            'item_id' => 'int',
            'cat_item_package_measurements_id' => 'int',
            'active' => 'bool'
        ];
        protected $fillable = [
            'item_id',
            'cat_item_package_measurements_id',
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
         * @return ItemPackageMeasurement
         */
        public function setItemId(int $item_id): ItemPackageMeasurement
        {
            $this->item_id = $item_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getCatItemPackageMeasurementsId(): int
        {
            return $this->cat_item_package_measurements_id;
        }

        /**
         * @param int $cat_item_package_measurements_id
         *
         * @return ItemPackageMeasurement
         */
        public function setCatItemPackageMeasurementsId(int $cat_item_package_measurements_id): ItemPackageMeasurement
        {
            $this->cat_item_package_measurements_id = $cat_item_package_measurements_id;
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
         * @return ItemPackageMeasurement
         */
        public function setActive(bool $active): ItemPackageMeasurement
        {
            $this->active = $active;
            return $this;
        }

        /**
         * @return CatItemPackageMeasurement
         */
        public function getCatItemPackageMeasurements(): CatItemPackageMeasurement
        {
            $e = CatItemPackageMeasurement::find($this->cat_item_package_measurements_id);
            if (empty($e)) $e = new CatItemPackageMeasurement();
            $this->cat_item_package_measurements = $e;
            $this->cat_item_package_measurements_id = $e->id;

            return $this->cat_item_package_measurements;
        }

        /**
         * @param CatItemPackageMeasurement $cat_item_package_measurements
         *
         * @return ItemPackageMeasurement
         */
        public function setCatItemPackageMeasurements(CatItemPackageMeasurement $cat_item_package_measurements): ItemPackageMeasurement
        {
            $this->cat_item_package_measurements = $cat_item_package_measurements;
            $this->cat_item_package_measurements_id = $cat_item_package_measurements->id;
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
