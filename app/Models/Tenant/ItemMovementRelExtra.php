<?php

    /**

     */

    namespace App\Models\Tenant;


    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * Class ItemMovementRelExtra
     *
     * @property int                         $id
     * @property int|null                    $item_id
     * @property int|null                    $item_movement_id
     * @property int|null                    $item_status_id
     * @property int|null                    $item_unit_business_id
     * @property int|null                    $item_mold_cavities_id
     * @property int|null                    $item_package_measurements_id
     * @property int|null                    $item_units_per_package_id
     * @property int|null                    $item_mold_properties_id
     * @property int|null                    $item_product_family_id
     * @property int|null                    $item_size_id
     * @property int|null                    $item_color_id
     * @property Carbon|null                 $created_at
     * @property Carbon|null                 $updated_at
     * @property Item|null                   $item
     * @property ItemMoldCavity|null         $item_mold_cavities
     * @property ItemMoldProperty|null       $item_mold_properties
     * @property ItemMovement|null           $item_movement
     * @property ItemPackageMeasurement|null $item_package_measurements
     * @property ItemProductFamily|null      $item_product_family
     * @property ItemSize|null               $item_size
     * @property ItemColor|null              $item_color
     * @property ItemStatus|null             $item_status
     * @property ItemUnitBusiness|null       $item_unit_business
     * @property ItemUnitsPerPackage|null    $item_units_per_package
     * @package App\Models
     * @method static Builder|ItemMovementRelExtra newModelQuery()
     * @method static Builder|ItemMovementRelExtra newQuery()
     * @method static Builder|ItemMovementRelExtra query()
     * @method static Builder|ItemMovementRelExtra whereCreatedAt($value)
     * @method static Builder|ItemMovementRelExtra whereId($value)
     * @method static Builder|ItemMovementRelExtra whereItemId($value)
     * @method static Builder|ItemMovementRelExtra whereItemMoldCavitiesId($value)
     * @method static Builder|ItemMovementRelExtra whereItemMoldPropertiesId($value)
     * @method static Builder|ItemMovementRelExtra whereItemMovementId($value)
     * @method static Builder|ItemMovementRelExtra whereItemPackageMeasurementsId($value)
     * @method static Builder|ItemMovementRelExtra whereItemProductFamilyId($value)
     * @method static Builder|ItemMovementRelExtra whereItemSizeId($value)
     * @method static Builder|ItemMovementRelExtra whereItemColorId($value)
     * @method static Builder|ItemMovementRelExtra whereItemStatusId($value)
     * @method static Builder|ItemMovementRelExtra whereItemUnitBusinessId($value)
     * @method static Builder|ItemMovementRelExtra whereItemUnitsPerPackageId($value)
     * @method static Builder|ItemMovementRelExtra whereUpdatedAt($value)
     * @mixin Eloquent
     * @mixin ModelTenant
     */
    class ItemMovementRelExtra extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'item_movement_rel_extra';
        protected $perPage = 25;

        protected $casts = [
            'item_id' => 'int',
            'item_movement_id' => 'int',
            'item_status_id' => 'int',
            'item_unit_business_id' => 'int',
            'item_mold_cavities_id' => 'int',
            'item_package_measurements_id' => 'int',
            'item_units_per_package_id' => 'int',
            'item_mold_properties_id' => 'int',
            'item_product_family_id' => 'int',
            'item_color_id' => 'int',
            'item_size_id' => 'int',
        ];

        protected $fillable = [
            'item_id',
            'item_movement_id',
            'item_status_id',
            'item_unit_business_id',
            'item_mold_cavities_id',
            'item_package_measurements_id',
            'item_units_per_package_id',
            'item_mold_properties_id',
            'item_product_family_id',
            'item_size_id',
            'item_color_id',
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
        public function item_mold_cavities()
        {
            return $this->belongsTo(ItemMoldCavity::class, 'item_mold_cavities_id');
        }

        /**
         * @return BelongsTo
         */
        public function item_mold_properties()
        {
            return $this->belongsTo(ItemMoldProperty::class, 'item_mold_properties_id');
        }

        /**
         * @return BelongsTo
         */
        public function item_movement()
        {
            return $this->belongsTo(ItemMovement::class);
        }

        /**
         * @return BelongsTo
         */
        public function item_package_measurements()
        {
            return $this->belongsTo(ItemPackageMeasurement::class, 'item_package_measurements_id');
        }

        /**
         * @return BelongsTo
         */
        public function item_product_family()
        {
            return $this->belongsTo(ItemProductFamily::class);
        }

        /**
         * @return BelongsTo
         */
        public function item_size()
        {
            return $this->belongsTo(ItemSize::class);
        }

        /**
         * @return BelongsTo
         */
        public function item_color()
        {
            return $this->belongsTo(ItemColor::class);
        }

        /**
         * @return BelongsTo
         */
        public function item_status()
        {
            return $this->belongsTo(ItemStatus::class);
        }

        /**
         * @return BelongsTo
         */
        public function item_unit_business()
        {
            return $this->belongsTo(ItemUnitBusiness::class);
        }

        /**
         * @return BelongsTo
         */
        public function item_units_per_package()
        {
            return $this->belongsTo(ItemUnitsPerPackage::class);
        }

        /**
         * @return int|null
         */
        public function getItemId(): ?int
        {
            return (int)$this->item_id;
        }

        /**
         * @param int|null $item_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemId(?int $item_id): ItemMovementRelExtra
        {
            $this->item_id = (int)$item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemMovementId(): ?int
        {
            return (int)$this->item_movement_id;
        }

        /**
         * @param int|null $item_movement_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemMovementId(?int $item_movement_id): ItemMovementRelExtra
        {
            $this->item_movement_id = (int)$item_movement_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemStatusId(): ?int
        {
            return (int)$this->item_status_id;
        }

        /**
         * @param int|null $item_status_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemStatusId(?int $item_status_id): ItemMovementRelExtra
        {
            $this->item_status_id = (int)$item_status_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemUnitBusinessId(): ?int
        {
            return (int)$this->item_unit_business_id;
        }

        /**
         * @param int|null $item_unit_business_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemUnitBusinessId(?int $item_unit_business_id): ItemMovementRelExtra
        {
            $this->item_unit_business_id = (int)$item_unit_business_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemMoldCavitiesId(): ?int
        {
            return (int)$this->item_mold_cavities_id;
        }

        /**
         * @param int|null $item_mold_cavities_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemMoldCavitiesId(?int $item_mold_cavities_id): ItemMovementRelExtra
        {
            $this->item_mold_cavities_id = (int)$item_mold_cavities_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemPackageMeasurementsId(): ?int
        {
            return (int)$this->item_package_measurements_id;
        }

        /**
         * @param int|null $item_package_measurements_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemPackageMeasurementsId(?int $item_package_measurements_id): ItemMovementRelExtra
        {
            $this->item_package_measurements_id = (int)$item_package_measurements_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemUnitsPerPackageId(): ?int
        {
            return (int)$this->item_units_per_package_id;
        }

        /**
         * @param int|null $item_units_per_package_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemUnitsPerPackageId(?int $item_units_per_package_id): ItemMovementRelExtra
        {
            $this->item_units_per_package_id = (int)$item_units_per_package_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemMoldPropertiesId(): ?int
        {
            return (int)$this->item_mold_properties_id;
        }

        /**
         * @param int|null $item_mold_properties_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemMoldPropertiesId(?int $item_mold_properties_id): ItemMovementRelExtra
        {
            $this->item_mold_properties_id = (int)$item_mold_properties_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemProductFamilyId(): ?int
        {
            return (int)$this->item_product_family_id;
        }

        /**
         * @param int|null $item_product_family_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemProductFamilyId(?int $item_product_family_id): ItemMovementRelExtra
        {
            $this->item_product_family_id = (int)$item_product_family_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getItemSizeId(): ?int
        {
            return (int)$this->item_size_id;
        }

        /**
         * @param int|null $item_size_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemSizeId(?int $item_size_id): ItemMovementRelExtra
        {
            $this->item_size_id = (int)$item_size_id;
            return $this;
        }


        /**
         * @return int|null
         */
        public function getItemColorId(): ?int
        {
            return (int)$this->item_color_id;
        }

        /**
         * @param int|null $item_color_id
         *
         * @return ItemMovementRelExtra
         */
        public function setItemColorId(?int $item_color_id): ItemMovementRelExtra
        {
            $this->item_color_id = (int)$item_color_id;
            return $this;
        }

    }
