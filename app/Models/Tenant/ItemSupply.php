<?php

    namespace App\Models\Tenant;


    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;


    /**
     * App\Models\Tenant\ItemSet
     *
     * @property int|null   $item_id
     * @property int|null   $individual_item_id
     * @property float|null $quantity
     * @property Item  $individual_item
     * @property Item  $item
     * @property Item  $relation_item
     * @method static Builder|ItemSet newModelQuery()
     * @method static Builder|ItemSet newQuery()
     * @method static Builder|ItemSet query()
     * @mixin ModelTenant
     * @mixin Eloquent
     */
    class ItemSupply extends ModelTenant
    {

        protected $fillable = [
            'item_id',
            'individual_item_id',
            'quantity',
        ];

        protected $casts = [
            'quantity' => 'float',
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
        public function individual_item()
        {
            return $this->belongsTo(Item::class, 'individual_item_id');
        }

        /**
         * @return BelongsTo
         */
        public function relation_item()
        {
            return $this->belongsTo(Item::class, 'individual_item_id');
        }

        /**
         * @return int
         */
        public function getItemId()
        {
            return (int)$this->item_id;
        }

        /**
         * @param int $item_id
         *
         * @return ItemSet
         */
        public function setItemId($item_id = 0)
        {
            $this->item_id = (int)$item_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getIndividualItemId()
        {
            return (int)$this->individual_item_id;
        }

        /**
         * @param int $individual_item_id
         *
         * @return ItemSet
         */
        public function setIndividualItemId($individual_item_id = 0)
        {
            $this->individual_item_id = (int)$individual_item_id;
            return $this;
        }

        /**
         * @return float
         */
        public function getQuantity()
        {
            return (float)$this->quantity;
        }

        /**
         * @param float $quantity
         *
         * @return ItemSet
         */
        public function setQuantity($quantity = 0)
        {
            $this->quantity = (float)$quantity;
            return $this;
        }
        public function getCollectionData(){
        $data = $this->toArray();
        $data['item'] = $this->item;
        $data['individual_item'] = $this->individual_item;
        return $data;

    }

    }
