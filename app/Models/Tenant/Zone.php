<?php


    namespace App\Models\Tenant;


    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
     * Class Zone
     *
     * @property int    $id
     * @property string|null $name
     * @package  App\Models\Tenant
     */
    class Zone extends ModelTenant
    {
        use UsesTenantConnection;

        public $timestamps = false;
        protected $perPage = 25;
        protected $fillable = [
            'name'
        ];

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
         * @return Zone
         */
        public function setName(?string $name): Zone
        {
            $this->name = $name;
            return $this;
        }

    }
