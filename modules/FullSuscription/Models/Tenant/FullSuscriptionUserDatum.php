<?php

    namespace Modules\FullSuscription\Models\Tenant;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
     * Class FullSuscriptionUserDatum
     *
     * @property int         $id
     * @property int|null    $person_id
     * @property string|null $discord_user
     * @property string|null $slack_channel
     * @property string|null $discord_channel
     * @property string|null $gitlab_user
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @package App\Models
     */
    class FullSuscriptionUserDatum extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'person_id' => 'int'
        ];

        protected $fillable = [
            'person_id',
            'discord_user',
            'slack_channel',
            'discord_channel',
            'gitlab_user'
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
         * @return FullSuscriptionUserDatum
         */
        public function setPersonId(?int $person_id): FullSuscriptionUserDatum
        {
            $this->person_id = $person_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDiscordUser(): ?string
        {
            return $this->discord_user;
        }

        /**
         * @param string|null $discord_user
         *
         * @return FullSuscriptionUserDatum
         */
        public function setDiscordUser(?string $discord_user): FullSuscriptionUserDatum
        {
            $this->discord_user = $discord_user;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getSlackChannel(): ?string
        {
            return $this->slack_channel;
        }

        /**
         * @param string|null $slack_channel
         *
         * @return FullSuscriptionUserDatum
         */
        public function setSlackChannel(?string $slack_channel): FullSuscriptionUserDatum
        {
            $this->slack_channel = $slack_channel;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDiscordChannel(): ?string
        {
            return $this->discord_channel;
        }

        /**
         * @param string|null $discord_channel
         *
         * @return FullSuscriptionUserDatum
         */
        public function setDiscordChannel(?string $discord_channel): FullSuscriptionUserDatum
        {
            $this->discord_channel = $discord_channel;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getGitlabUser(): ?string
        {
            return $this->gitlab_user;
        }

        /**
         * @param string|null $gitlab_user
         *
         * @return FullSuscriptionUserDatum
         */
        public function setGitlabUser(?string $gitlab_user): FullSuscriptionUserDatum
        {
            $this->gitlab_user = $gitlab_user;
            return $this;
        }

    }
