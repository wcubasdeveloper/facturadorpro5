<?php

    namespace Modules\Production\Exports;

    use Illuminate\Contracts\View\View;
    use Illuminate\Database\Eloquent\Collection;
    use Maatwebsite\Excel\Concerns\Exportable;
    use Maatwebsite\Excel\Concerns\FromView;
    use Maatwebsite\Excel\Concerns\ShouldAutoSize;

    /**
     *  bas LedgerAccountExcelExport
     * Class PackagingExport
     *
     * @package Modules\Production\Exports
     */
    class PackagingExport implements FromView, ShouldAutoSize
    {
        use Exportable;

        /** @var Collection */
        protected $collection;

        public function view(): View
        {
            $records = $this->getCollection();
            return view('production::packaging.partial.export',
                compact(
                    'records'
                ));

        }

        /**
         * @return Collection
         */
        public function getCollection(): Collection
        {
            return $this->collection;
        }

        /**
         * @param Collection $collection
         *
         * @return PackagingExport
         */
        public function setCollection(Collection $collection): PackagingExport
        {
            $this->collection = $collection;
            return $this;
        }


    }
