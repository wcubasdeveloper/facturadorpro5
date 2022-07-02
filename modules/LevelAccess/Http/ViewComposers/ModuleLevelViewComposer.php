<?php

namespace Modules\LevelAccess\Http\ViewComposers;

use Illuminate\Support\Facades\DB;
use Modules\LevelAccess\Models\ModuleLevel;

/**
 * Class ModuleLevelViewComposer
 *
 * @package Modules\LevelAccess\Http\ViewComposers
 */
class ModuleLevelViewComposer
{
    /**
     * @param $view
     */
    public function compose($view)
    {
        /** @var \App\Models\Tenant\User $user */
        $user = auth()->user();
        $myLevels = $user
            ->getCurrentModuleLevelByTenant()
            ->pluck('module_level_id')
            ->toArray();

        $module_levels = ModuleLevel::whereIn('id', $myLevels)
            ->get()
            ->pluck('value')
            ->toArray();
        if(count($module_levels) > 0) {
            $view->vc_module_levels = $module_levels;
        } else {
            $view->vc_module_levels = ModuleLevel::all()->pluck('value')->toArray();
        }
    }
}
