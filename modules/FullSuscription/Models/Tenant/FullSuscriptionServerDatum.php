<?php


    namespace Modules\FullSuscription\Models\Tenant;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;


    /**
     * Class FullSuscriptionServerDatum
     *
     * @property int         $id
     * @property int|null    $person_id
     * @property string|null $host
     * @property string|null $ip
     * @property string|null $user
     * @property string|null $password
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @package App\Models
     */
    class FullSuscriptionServerDatum extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'person_id' => 'int'
        ];
        protected $fillable = [
            'person_id',
            'host',
            'ip',
            'user',
            'password'
        ];

        /**
         * @return int|null
         */
        public function getPersonId(): ?int
        {
            return $this->person_id;
        }

        /**
         * @param int|null $person_id
         *
         * @return FullSuscriptionServerDatum
         */
        public function setPersonId(?int $person_id): FullSuscriptionServerDatum
        {
            $this->person_id = $person_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getHost(): ?string
        {
            return $this->host;
        }

        /**
         * @param string|null $host
         *
         * @return FullSuscriptionServerDatum
         */
        public function setHost(?string $host): FullSuscriptionServerDatum
        {
            $this->host = $host;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getIp(): ?string
        {
            return $this->ip;
        }

        /**
         * @param string|null $ip
         *
         * @return FullSuscriptionServerDatum
         */
        public function setIp(?string $ip): FullSuscriptionServerDatum
        {
            $this->ip = $ip;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getUser(): ?string
        {
            return $this->user;
        }

        /**
         * @param string|null $user
         *
         * @return FullSuscriptionServerDatum
         */
        public function setUser(?string $user): FullSuscriptionServerDatum
        {
            $this->user = $user;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPassword(): ?string
        {
            return $this->password;
        }

        /**
         * @param string|null $password
         *
         * @return FullSuscriptionServerDatum
         */
        public function setPassword(?string $password): FullSuscriptionServerDatum
        {
            $this->password = $password;
            return $this;
        }
    }
