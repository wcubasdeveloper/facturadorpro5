<?php

namespace Modules\Finance\Helpers; 

use Illuminate\Support\Facades\Storage;
use Validator;


class UploadFileHelper
{ 

    public static function validateUploadFile($request, $column = 'file', $mimes = 'jpg,jpeg,png,gif,svg,pdf,xlsx')
    {
        
        $validator = Validator::make($request->all(), [
            $column => 'mimes:'.$mimes
        ]);

        if ($validator->fails()) { 
            return [
                'success' => false,
                'message' =>  'Tipo de archivo no permitido',
            ];
        }

        return [
            'success' => true,
            'message' =>  '',
        ];

    } 

     
    /**
     * 
     * Obtener archivo temporal en base64
     *
     * @param  $request
     * @return array
     */
    public static function getTempFile($request)
    {
        $file = $request['file'];
        $type = $request['type'];

        $temp = tempnam(sys_get_temp_dir(), $type);
        file_put_contents($temp, file_get_contents($file));

        $mime = mime_content_type($temp);
        $data = file_get_contents($temp);

        return [
            'success' => true,
            'data' => [
                'filename' => $file->getClientOriginalName(),
                'temp_path' => $temp,
                'temp_image' => 'data:' . $mime . ';base64,' . base64_encode($data)
            ]
        ];
    }

    
    /**
     * 
     * Cargar archivo
     *
     * @param  string $folder
     * @param  string $old_filename
     * @param  string $temp_path
     * @param  int $id
     * @param  string $prefix
     * @return string
     */
    public static function uploadFileFromTempFile($folder, $old_filename, $temp_path, $id, $prefix = null)
    {

        $directory = 'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;
        $old_filename_array = explode('.', $old_filename);
        $now = date('YmdHis');

        $filename =  ($prefix ? "{$prefix}_" : "")."{$id}_{$now}".'.'.end($old_filename_array);

        Storage::put($directory.$filename, file_get_contents($temp_path));

        return $filename;
    }


}
