<?php

namespace App\Http\Controllers\System\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\RestaurantPartner;

class RestaurantPartnerController extends Controller
{
    public function list() {
        $partners = RestaurantPartner::get()->transform(function($row) {
            return $row->getCollectionData();
        });
        return [
            'success' => true,
            'data' => $partners,
            'message' => 'Listado de Partners'
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
            'email' => 'required|email|unique:restaurant_partners,email|max:250',
            'gitlab_user' => 'required|alpha_num|unique:restaurant_partners,gitlab_user|max:200',
            'domain' => 'required|url|unique:restaurant_partners,domain|max:250',
            'department_id' => 'required',

        ]);

        $partner = new RestaurantPartner();
        $partner->fill($request->all());
        $partner->save();

        return [
            'success' => true,
            'data' => $partner,
            'message' => 'Partner registrado exitosamente'
        ];
    }

    public function search(Request $request)
    {
        $request->validate([
            'id' => 'nullable|numeric',
            'full_name' => 'nullable|regex:/^[\pL\s\-]+$/u|max:200|required_without_all:id,full_name,email,gitlab_user,domain',
            'email' => 'nullable|email|max:250',
            'gitlab_user' => 'nullable|alpha_num|max:200',
            'domain' => 'nullable|max:250',
        ]);

        $partners = RestaurantPartner::SearchId($request->id)
                    ->SearchName($request->full_name)
                    ->SearchEmail($request->email)
                    ->SearchDomain($request->domain)
                    ->SearchGitlab($request->gitlab_user)
                    ->get()
                    ->transform(function($row) {
                        return $row->getCollectionData();
                    });

        return [
            'success' => true,
            'data' => $partners,
            'message' => count($partners) > 0 ? 'Partners encontrados' : 'Partners no encontrados'
        ];
    }
}
