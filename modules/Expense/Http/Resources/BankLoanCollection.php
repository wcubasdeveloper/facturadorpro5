<?php

    namespace Modules\Expense\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Illuminate\Support\Collection;
    use Modules\Expense\Models\BankLoan;

    /**
     * Class BankLoanCollection
     *
     * @mixin ResourceCollection
     * @package Modules\Expense\Http\Resources
     */
    class BankLoanCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         *
         * @return \Illuminate\Support\Collection
         */
        public function toArray($request): Collection
        {
            $col =$this->collection->transform(function (BankLoan $row, $key) {

                $data = $row->toArray();

                $a =  [
                    'id' => $row->id,
                    'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                    'number' => $row->number,
                    // 'supplier_name' => $row->supplier->name,
                    // 'supplier_number' => $row->supplier->number,
                    'currency_type_id' => $row->currency_type_id,
                    'state_type_id' => $row->state_type_id,
                    'total' => $row->total,
                    // 'expense_type_description' => $row->expense_type->description,
                    // 'expense_reason_description' => $row->expense_reason->description,

                    'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
                ];
                return array_merge($data,$a);
            });;
            return $col;
        }

    }
