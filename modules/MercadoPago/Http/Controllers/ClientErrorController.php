<?php

namespace Modules\MercadoPago\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MercadoPago\Models\ClientError;
use Modules\MercadoPago\Http\Resources\ClientErrorCollection;


class ClientErrorController extends Controller
{ 
 
    public function records()
    {
        
        $client_error = ClientError::get();

        return new ClientErrorCollection($client_error);

    }

}
