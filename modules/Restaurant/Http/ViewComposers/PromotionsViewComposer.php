<?php

namespace Modules\Restaurant\Http\ViewComposers;

use App\Models\Tenant\Promotion;


class PromotionsViewComposer
{
    public function compose($view)
    {
        $view->items = Promotion::where('apply_restaurant', 1)->get();
    }
}