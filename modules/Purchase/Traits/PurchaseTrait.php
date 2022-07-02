<?php

namespace Modules\Purchase\Traits;

use App\Models\Tenant\{
    Purchase,
    Cash
}; 

trait PurchaseTrait
{
 
    public function createCashDocument(){

        Purchase::created(function ($purchase) {
            
            $cash = Cash::whereActive()->first();

            if($cash){
                $cash->cash_documents()->create(['purchase_id' => $purchase->id]); 
            }

        });

    }

}
