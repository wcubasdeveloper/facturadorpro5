<?php

    namespace App\Services;

    use Modules\Item\Models\ItemLotsGroup;

    class ItemLotsGroupService
    {

        /**
         * Devuelve un string con solo lotes
         *
         * @param $id
         *
         * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|string
         */
        public function getLote($id)
        {
            $result = '';
            if (is_array($id)) {
                foreach ($id as $item) {
                    $result .= "/" . $item->code;
                }
            } else {
                $record = ItemLotsGroup::where('id', $id)->first();
                if ($record) {
                    $result = $record->code;
                }
            }
            return $result;
        }

        /**
         * Devuelve un string con Lote y fecha de vencimiento.
         *
         * @param $id
         *
         * @return string
         */
        public function getLoteWithDate($id)
        {
            $result = '';
            if (is_array($id)) {
                foreach ($id as $item) {
                    $result .= "/" . $item->code . " V:" . $item->date_of_due;
                }
            } else {
                $record = ItemLotsGroup::where('id', $id)->first();

                if ($record) {
                    $result = $record->code . " V:" . $record->date_of_due;
                }
            }
            return $result;
        }


        /**
         * Devuelve la fecha de vencimiento del lote
         * @param $id
         *
         * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|string
         */
        public function getLotDateOfDue($id){
            $result = '';
            if (is_array($id)) {
                foreach ($id as $item) {
                    $result .= "/"  . $item->date_of_due;
                }
            } else {
                $record = ItemLotsGroup::where('id', $id)->first();

                if ($record) {
                    $result = $record->date_of_due;
                }
            }
            return $result;

        }
    }
