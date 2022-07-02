<?php

    /**

     */

    namespace App\Models\Tenant;


    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class CatItemSize
     *
     * @property int                   $id
     * @property string                $name
     * @property Carbon|null           $created_at
     * @property Carbon|null           $updated_at
     * @property Collection|ItemSize[] $item_sizes
     * @package App\Models
     * @property-read int|null         $item_sizes_count
     * @method static Builder|CatItemSize newModelQuery()
     * @method static Builder|CatItemSize newQuery()
     * @method static Builder|CatItemSize query()
     * @method static Builder|CatItemSize whereCreatedAt($value)
     * @method static Builder|CatItemSize whereId($value)
     * @method static Builder|CatItemSize whereName($value)
     * @method static Builder|CatItemSize whereUpdatedAt($value)
     * @mixin Eloquent
     * @mixin ModelTenant
     */
    class CatItemSize extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'cat_item_size';
        protected $perPage = 25;

        protected $fillable = [
            'name'
        ];

        public function item_sizes()
        {
            return $this->hasMany(ItemSize::class);
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

        /**
         * @param string $name
         *
         * @return CatItemSize
         */
        public function setName(string $name = ''): CatItemSize
        {
            $this->name = (string)$name;
            return $this;
        }

           }
