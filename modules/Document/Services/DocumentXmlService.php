<?php

namespace Modules\Document\Services;

class DocumentXmlService
{
    
    public function getGlobalChargesNoBase($document)
    { 
        // Cargos globales que no afectan la base imponible del IGV/IVAP
        $tot_charges = 0;

        if($document->charges){

            $tot_charges = collect($document->charges)->sum(function($charge){
                return (in_array($charge->charge_type_id, ['50', '46'])) ? $charge->amount : 0;
            });
        }

        return $tot_charges;
    }


    public function getGlobalDiscountsNoBase($document)
    { 

        //descuentos globales que no afectan la base
        $allowance_total_amount = 0;
    
        if($document->discounts){
            
            $allowance_total_amount = collect($document->discounts)->sum(function($discount){
                return (in_array($discount->discount_type_id, ['03', '63'])) ? $discount->amount : 0;
            });

        }

        return $allowance_total_amount;
    }

    public function getItemsDiscountsNoBase($document)
    { 

        return $document->items->sum(function($row){
            return $row->discounts ? collect($row->discounts)->sum(function($discount){
                return $discount->discount_type_id == '01' ? $discount->amount : 0;
            }) : 0;
        });

    }


    public function hasDiscountsNoBase($document)
    { 

        $has_discounts_no_base = false;
        
        if($document->discounts){
            
            $discount = collect($document->discounts)->first(function($discount){
                return in_array($discount->discount_type_id, ['03', '63']);
            });

            if($discount) $has_discounts_no_base = true;
        }
        

        $total_items_no_base = $this->getItemsDiscountsNoBase($document);
        if($total_items_no_base > 0) $has_discounts_no_base = true;

        // dd($total_items_no_base, $has_discounts_no_base);

        return $has_discounts_no_base;
    }

    
    public function hasDiscountsNoBaseByInputs($inputs)
    { 

        $has_discounts_no_base = false;

        if(array_key_exists('discounts', $inputs)) {

            if($inputs['discounts']){

                $discount = collect($inputs['discounts'])->first(function($row){
                    return in_array($row['discount_type_id'], ['03', '63']);
                });
    
                if($discount) $has_discounts_no_base = true;
            }
        }

        $total_items_no_base = $this->getItemsDiscountsNoBaseByInputs($inputs);
        if($total_items_no_base > 0) $has_discounts_no_base = true;

        return $has_discounts_no_base;
    }


    public function getItemsDiscountsNoBaseByInputs($inputs)
    {

        $total_items_no_base = 0;

        if(array_key_exists('items', $inputs)) {

            $total_items_no_base = collect($inputs['items'])->sum(function($row){

                $sum_total_items = 0;

                if(array_key_exists('discounts', $row)){
                    if($row['discounts']){
                        $sum_total_items = collect($row['discounts'])->sum(function($discount){
                            return $discount['discount_type_id'] == '01' ? $discount['amount'] : 0;
                        });
                    }
                }

                return $sum_total_items;
            });

        }

        return $total_items_no_base;
    }


}
