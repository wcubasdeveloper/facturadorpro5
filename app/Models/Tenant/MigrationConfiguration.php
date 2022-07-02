<?php


namespace App\Models\Tenant;

use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;

/**
 * Class MigrationConfiguration
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $api_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MigrationConfiguration extends ModelTenant
{
    use UsesTenantConnection;
    protected $perPage = 25;
    protected $table = 'migration_configuration';

    protected $fillable = [
        'url',
        'api_key',
    ];

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return MigrationConfiguration
     */
    public function setUrl(?string $url): MigrationConfiguration
    {
        $this->url = str_replace(['https://','http://','/'],'',$url);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    /**
     * @param string|null $api_key
     * @return MigrationConfiguration
     */
    public function setApiKey(?string $api_key): MigrationConfiguration
    {
        $this->api_key = $api_key;
        return $this;
    }

    /**
     * Devuelve un json con las propiedades excluidas
     *
     * @return string
     */
    public static function getPublicConfig(){
        return json_encode(self::getCollectionData());

    }


    public static function getCollectionData()
    {
        $conf = self::first();
        if(empty($conf)) {
            $conf = new self();
        }
        return [
            'url' => $conf->getUrl(),
            'api_key'=>$conf->getApiKey(),
        ];
    }

    }
