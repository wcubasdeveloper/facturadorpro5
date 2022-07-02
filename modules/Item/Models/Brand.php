<?php

    namespace Modules\Item\Models;

    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * Class Brand
     *
     * @property int               $id
     * @property string|null             $name
     * @property Carbon|null       $created_at
     * @property Carbon|null       $updated_at
     * @property Collection|Item[] $items
     * @mixin ModelTenant
     * @package App\Models
     * @property-read int|null     $items_count
     * @method static Builder|Brand newModelQuery()
     * @method static Builder|Brand newQuery()
     * @method static Builder|Brand query()
     */
    class Brand extends ModelTenant
    {

        protected $fillable = [
            'name',
        ];

        /**
         * @return HasMany
         */
        public function items()
        {
            return $this->hasMany(Item::class);
        }

        /**
         * @return string|null
         */
        public function getName(): ?string
        {
            return $this->name;
        }

        /**
         * @param string|null $name
         *
         * @return Brand
         */
        public function setName(?string $name): Brand
        {
            $this->name = $name;
            return $this;
        }



    }
