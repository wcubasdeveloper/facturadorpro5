<?php
namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\UserRequest;
use App\Http\Resources\System\UserResource;
use App\Models\System\User;
use Hyn\Tenancy\Environment;
use App\Models\System\Client;
use Illuminate\Support\Facades\DB;
use App\Models\System\Configuration;

class UserController extends Controller
{
    public function create()
    {
        return view('system.users.form');
    }

    public function record()
    {
        $user = User::first();

        return new UserResource($user);
    }

    public function store(UserRequest $request)
    {
        $id = $request->input('id');
        $user = User::firstOrNew(['id' => $id]);

        if (config('tenant.password_change')) {
            $user->email = $request->input('email');
            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
        }

        if (strlen($request->input('password')) > 0) {
            if (config('tenant.password_change')) {
                $user->password = bcrypt($request->input('password'));
            }
        }
        $user->save();

        $configuration = Configuration::first();
        $configuration->enable_whatsapp = $request->input('enable_whatsapp');
        $configuration->save();
        $this->updatePhoneClients($request->input('phone'), $request->input('enable_whatsapp'));

        return [
            'success' => true,
            'message' => 'Usuario actualizado'
        ];
    }


    public function updatePhoneClients($phone, $enable_whatsapp){

        DB::connection('system')->transaction(function () use ($phone, $enable_whatsapp) {

            $records = Client::get();

            foreach ($records as $row) {

                $tenancy = app(Environment::class);
                $tenancy->tenant($row->hostname->website);

                DB::connection('tenant')->table('configurations')->where('id', 1)->update([
                    'phone_whatsapp' => $phone,
                    'enable_whatsapp' => $enable_whatsapp
                ]);
            }

        });

    }


    public function getPhone()
    {
        $user = User::first();

        $user_resource = new UserResource($user);

        return $user_resource->phone;
    }

}
