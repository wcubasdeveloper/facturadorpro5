<?php

    namespace Modules\DocumentaryProcedure\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Carbon\Carbon;
    use Modules\DocumentaryProcedure\Models\DocumentaryFile;

    /**
     * Class DocumentaryFileCollection
     *
     * @package Modules\DocumentaryProcedure\Http\Resources
     */
    class DocumentaryFileCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         *
         * @return mixed
         */
        public function toArray($request)
        {

            $holyday = self::getHolyday();

            return $this->collection->transform(function (DocumentaryFile $row, $key) use ($holyday) {
                $data = $row->getCollectionData($holyday);

                return $data;

            });
        }

        public static function getHolyday()
        {
            $year = Carbon::now()->format('Y');

            // Dias feriados
            return [
                '01-01-' . $year,
                '01-04-' . $year,
                '02-04-' . $year,
                '01-05-' . $year,
                '02-06-' . $year,
                '28-07-' . $year,
                '29-07-' . $year,
                '30-08-' . $year,
                '08-10-' . $year,
                '01-11-' . $year,
                '08-12-' . $year,
                '25-12-' . $year,
            ];
        }
    }
