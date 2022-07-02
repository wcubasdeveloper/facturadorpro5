<?php

namespace App\Models\System;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Module
 *
 * @package App\Models\System
 * @mixin Model
 */
class Module extends Model
{
    use UsesSystemConnection;

    protected $fillable = [
        'value',
        'sort',
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function levels()
    {
        return $this->hasMany(ModuleLevel::class, 'module_id');
    }

    /**
     * @return string
     */
    public function getDescription()
    : string {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Module
     */
    public function setDescription(string $description)
    : Module {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    : string {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return Module
     */
    public function setValue(string $value)
    : Module {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getSort()
    : int {
        return $this->sort;
    }

    /**
     * @param int $sort
     *
     * @return Module
     */
    public function setSort(int $sort)
    : Module {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return $this
     */
    public function setLastSortInt(){
        $this->setSort(self::where('id','!=',$this->id)->select('sort')->max('sort'));
        return $this;
    }
}
