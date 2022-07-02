<?php

namespace Modules\Pos\Traits;

use Modules\Pos\Models\Tip;
use App\CoreFacturalo\Requests\Inputs\DocumentInput;
use App\Models\Tenant\{
    Company,
};

trait TipTrait
{
    
    /**
     * Registrar propina
     *
     * @param $model
     * @param array $data
     * @return void
     */
    public function createTip($model, $data) 
    {
        if(!is_null($data))
        {
            $model->tip()->create($data);
        }
    }

        
    /**
     * Actualizar propina
     *
     * @param  $model
     * @param  array $data
     * @return void
     */
    public function updateTip($model, $data) 
    {
        $tip = $model->tip;

        if(!is_null($data) && !is_null($tip))
        {
            if($model->date_of_issue != $tip->origin_date_of_issue)
            {
                $model->tip()->update($data);
            }
        }
    }


    /**
     * Retorna datos para registro de propina
     *
     * Usado en:
     * TipServiceProvider - sale_note
     * 
     * @param  array $inputs
     * @return array
     */
    public function getTipFromRequest($inputs)
    {
        return DocumentInput::tip($inputs, $this->getCompanySoapTypeId());
    }
    

    /**
     * @return string
     */
    public function getCompanySoapTypeId()
    {
        return Company::getCompanySoapTypeId();
    }


    /**
     * 
     * Obtener datos para actualizar la propina cuando cambia el modelo relacionado
     * 
     * @return array
     */
    public function getDataForUpdate($model)
    {
        return [
            'origin_date_of_issue' => $model->date_of_issue
        ];
    }

}
