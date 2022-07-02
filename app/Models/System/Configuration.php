<?php

namespace App\Models\System;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use UsesSystemConnection;

    protected $fillable = [
        'locked_admin',
        'certificate',
        'soap_send_id',
        'soap_type_id',
        'soap_username',
        'soap_password',
        'soap_url',
        'token_public_culqui',
        'token_private_culqui',
        'url_apiruc',
        'token_apiruc',
        'apk_url',
        'login',
        'use_login_global'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function (self $item) {

            // if(empty($item->apk_url)) $item->apk_url = 'https://facturaloperu.com/apk/app-debug.apk';
        });
        static::retrieved(function (self $item) {

            // if (empty($item->apk_url)) $item->apk_url = 'https://facturaloperu.com/apk/app-debug.apk';
        });

    }

    public function getUseLoginGlobalAttribute($value)
    {
        return $value ? true : false;
    }

    public function setLoginAttribute($value)
    {
        $this->attributes['login'] = is_null($value) ? null : json_encode($value);
    }

    public function getLoginAttribute($value)
    {
        return is_null($value) ? null : (object) json_decode($value);
    }


    public static function getApiServiceToken(){
        $configuration = self::first();
        // $api_service_token = $configuration->token_apiruc =! '' ? $configuration->token_apiruc : config('configuration.api_service_token');
        $api_service_token = $configuration->token_apiruc == 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;
        return $api_service_token;
    }


}
