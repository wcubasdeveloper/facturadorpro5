<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class RestaurantPartner extends Model
{
    use UsesSystemConnection;

    protected $table = "restaurant_partners";

    protected $fillable = [
        'full_name',
        'email',
        'gitlab_user',
        'domain',
        'status',
        'department_id',
        'zone'
    ];

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @return array
     */
    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'gitlab_user' => $this->gitlab_user,
            'domain' => $this->domain,
            'status' => $this->status,
            'department_id' => $this->department_id,
            'zone' => $this->zone,
        ];
    }

    public function scopeSearchId($query, $id = null)
    {
        if($id){
            return $query->where('id', $id);
        }
    }

    public function scopeSearchName($query, $name = null)
    {
        if($name){
            return $query->where('full_name', 'LIKE', '%'.$name.'%');
        }
    }

    public function scopeSearchEmail($query, $email = null)
    {
        if($email){
            return $query->where('email', $email);
        }
    }

    public function scopeSearchDomain($query, $domain = null)
    {
        if($domain){
            return $query->where('domain', 'LIKE', '%'.$domain.'%');
        }
    }

    public function scopeSearchGitlab($query, $user = null)
    {
        if($user){
            return $query->where('gitlab_user', 'LIKE', '%'.$user.'%');
        }
    }
}
