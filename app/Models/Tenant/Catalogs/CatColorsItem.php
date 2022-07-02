<?php


    namespace App\Models\Tenant\Catalogs;


    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Class CatColorsItem
     *
     * @property int         $id
     * @property string      $name
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @package App\Models
     * @method static Builder|CatColorsItem newModelQuery()
     * @method static Builder|CatColorsItem newQuery()
     * @method static Builder|ModelCatalog orderByDescription()
     * @method static Builder|CatColorsItem query()
     * @method static Builder|ModelCatalog whereActive()
     * @mixin Eloquent
     */
    class CatColorsItem extends ModelCatalog
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $fillable = [
            'name'
        ];

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
         * @return CatColorsItem
         */
        public function setName(string $name): CatColorsItem
        {
            $this->name =  ucfirst(trim($name));
            return $this;
        }


    }
