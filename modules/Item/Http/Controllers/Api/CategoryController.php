<?php

namespace Modules\Item\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\Category;
use Modules\Item\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{

    public function records()
    {
        return [
            'success' => true,
            'data' => new CategoryCollection(Category::get())
        ];
    }

}
