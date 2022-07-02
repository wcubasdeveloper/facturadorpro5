<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreFacturalo\Facturalo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\User;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\Models\Tenant\Person;

class CompanyController extends Controller
{
   
    public function record(Request $request) {

        $user = new User();
        if(\Auth::user()){
            $user = \Auth::user();
        }

        $establishment_id =  $user->establishment_id;
        $establishments = Establishment::without(['country', 'department', 'province', 'district'])->where('id', $establishment_id)->get();
        $series = $user->getSeries();
        $customers = Person::without(['country', 'department', 'province', 'district'])
                               ->whereType('customers')
                               ->whereIsEnabled()
                               ->orderBy('name')
                               ->take(200)
                               ->get()->transform(function ($row) {
                                    return [
                                        'id'                                     => $row->id,
                                        'codigo_tipo_documento_identidad'        => $row->identity_document_type_id,
                                        'numero_documento'                       => $row->number,
                                        'apellidos_y_nombres_o_razon_social'     => $row->name,
                                        'codigo_pais'                            => $row->country_id,
                                        'direccion'                              => $row->address,
                                        'correo_electronico'                     => $row->email,
                                        'telefono'                               => $row->telephone,
                                    ];

                                });

        return [
            'series' => $series,
            'establishments' => $establishments,
            'company' =>  Company::active(),
            'customers' => $customers,
        ];

    }
}
