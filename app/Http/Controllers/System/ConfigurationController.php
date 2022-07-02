<?php
namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\System\Configuration;
use Illuminate\Support\Facades\DB;
use App\Models\System\Client;
use Hyn\Tenancy\Environment;


class ConfigurationController extends Controller
{

    public function index()
    {
        $configuration = Configuration::first();
        return view('system.configuration.index', compact('configuration'));
    }

    public function record()
    {

        $configuration = Configuration::first();

        return [
            'token_public_culqui' => $configuration->token_public_culqui,
            'token_private_culqui' => $configuration->token_private_culqui,
        ];
    }


    public function store(Request $request)
    {
        $configuration = Configuration::first();

        if($request->token_public_culqui)
        {
            $configuration->token_public_culqui = $request->token_public_culqui;
        }

        if($request->token_private_culqui)
        {
            $configuration->token_private_culqui = $request->token_private_culqui;
        }

        if($request->url_apiruc)
        {
            $configuration->url_apiruc = $request->url_apiruc;
        }

        if($request->token_apiruc)
        {
            $configuration->token_apiruc = $request->token_apiruc;
        }

        if($request->apk_url)
        {
            $configuration->apk_url = $request->apk_url;
            $this->updateApkUrl($request->apk_url);
        }

        $configuration->save();

        return [
            'success' => true,
            'message' => 'Datos guardados con exito'
        ];
    }

    public function apiruc()
    {

        $configuration = Configuration::first();

        return [
            'url_apiruc' => $configuration->url_apiruc,
            'token_apiruc' => $configuration->token_apiruc,
        ];
    }

    public function storeLoginSettings()
    {
        request()->validate([
            'position_form' => 'required|in:left,right',
            'show_logo_in_form' => 'required|boolean',
            'position_logo' => 'required|in:top-left,top-right,bottom-left,bottom-right,none',
            'show_socials' => 'required|boolean',
            'use_login_global' => 'required|boolean',
        ]);

        $config = Configuration::first();
        $loginConfig = $config->login;
        foreach(request()->all() as $key => $option) {
            $loginConfig->$key = $option;
        }

        $config->login = $loginConfig;
        $config->use_login_global = request('use_login_global');
        $config->save();

        return response()->json([
            'success' => true,
            'message' => 'Información actualizada.',
        ], 200);
    }

    public function storeBgLogin()
    {
        request()->validate([
            'image' => 'required|mimes:webp,jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $config = Configuration::first();
        if (request()->hasFile('image') && request()->file('image')->isValid()) {
            $file = request()->file('image');
            $ext = $file->getClientOriginalExtension();
            $name = time() . '.' . $ext;
            $path = 'public/uploads/login';
            $file->storeAs($path, $name);

            $loginConfig = $config->login;
            $basePathStorage = 'storage/uploads/login/';
			if (request('type') === 'bg') {
                $loginConfig->type = 'image';
				$loginConfig->image = asset($basePathStorage . $name);
			} else {
                $loginConfig->logo = asset($basePathStorage . $name);
            }
            $config->login = $loginConfig;
            $config->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Información actualizada.',
        ], 200);
    }

    public function InfoIndex(){
        $memory_limit = ini_get('memory_limit');
        $memory_in_byte = number_format(self::return_bytes($memory_limit),'2',',','.');
        $pcre_backtrack_limit = ini_get('pcre.backtrack_limit');
        $all_config = [
            'max_execution_time' =>ini_get('max_execution_time'),
            'max_input_time' =>ini_get('max_input_time'),
            'post_max_size' =>ini_get('post_max_size'),
            'upload_max_filesize' =>ini_get('upload_max_filesize'),
            'request_terminate_timeout' =>ini_get('request_terminate_timeout'),
            'date_timezone' =>ini_get('date.timezone'),
            'version_laravel' => app()->version(),
        ];

        return view('system.configuration.info', compact(
            'memory_limit',
            'memory_in_byte',
            'pcre_backtrack_limit',
            'all_config'
        ));

    }

    private  static function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = substr($val, 0, -1);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    public function apkurl()
    {

        $configuration = Configuration::first();

        return [
            'apk_url' => $configuration->apk_url
        ];
    }

    public function updateApkUrl ($apk_url)
    {
        DB::connection('system')->transaction(function () use ($apk_url) {

            $records = Client::get();

            foreach ($records as $row) {

                $tenancy = app(Environment::class);
                $tenancy->tenant($row->hostname->website);

                DB::connection('tenant')->table('configurations')->where('id', 1)->update(['apk_url' => $apk_url]);

            }

        });
    }

    
    /**
     * 
     * Actualizar el descuento global a 02 (Afecta la base) en todos los clientes
     *
     * @return array
     */
    public function updateTenantDiscountTypeBase()
    {

        DB::connection('system')->transaction(function (){

            $records = Client::get();

            foreach ($records as $row) 
            {
                $tenancy = app(Environment::class);
                $tenancy->tenant($row->hostname->website);

                DB::connection('tenant')->table('configurations')->where('id', 1)->update(['global_discount_type_id' => '02']);
            }

        });

        return [
            'success' => true,
            'message' => 'El proceso se realizó correctamente'
        ];
    }


}
