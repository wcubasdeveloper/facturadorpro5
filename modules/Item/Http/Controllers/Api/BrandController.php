<?php

namespace Modules\Item\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\Brand;
use Modules\Item\Http\Resources\BrandCollection;

class BrandController extends Controller
{

    public function records()
    {
        return [
            'success' => true,
            'data' => new BrandCollection(Brand::get())
        ];
    }

}
