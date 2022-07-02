<?php


    namespace App\Models\Tenant\Catalogs;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
     * Class CatItemPackageMeasurement
     *
     * @property int         $id
     * @property string      $name
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @package App\Models
     */
    class CatItemPackageMeasurement extends ModelTenant
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
         * @return $this
         */
        public function setName(string $name): CatItemPackageMeasurement
        {
            $this->name = ucfirst(trim($name));
            return $this;
        }
    }
