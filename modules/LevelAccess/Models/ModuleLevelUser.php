<?php

namespace Modules\LevelAccess\Models;

use App\Models\Tenant\User;
use App\Models\Tenant\Module;
use App\Models\Tenant\ModelTenant;

/**
 * Class ModuleLevelUser
 *
 * @package Modules\LevelAccess\Models
 * @mixin \Eloquent
 * @property-read \Modules\LevelAccess\Models\ModuleLevel $module_level
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleLevelUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleLevelUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleLevelUser query()
 */
class ModuleLevelUser extends ModelTenant
{

    protected $table = 'module_level_user';
    protected $fillable = [
        'user_id',
        'module_level_id',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module_level() {

        return $this->belongsTo(ModuleLevel::class);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {

        return $this->belongsTo(User::class);

    }

    /**
     * @return int
     */
    public function getModuleUserId()
    : int {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return ModuleLevelUser
     */
    public function setModuleUserId(int $user_id)
    : ModuleLevelUser {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getModuleLevelId()
    : int {
        return $this->module_level_id;
    }

    /**
     * @param int $module_level_id
     *
     * @return ModuleLevelUser
     */
    public function setModuleLevelId(int $module_level_id)
    : ModuleLevelUser {
        $this->module_level_id = $module_level_id;
        return $this;
    }

}
