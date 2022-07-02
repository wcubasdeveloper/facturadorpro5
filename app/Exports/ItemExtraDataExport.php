<?php

    namespace App\Exports;

    use Illuminate\Contracts\View\View;
    use Illuminate\Database\Eloquent\Collection;
    use Maatwebsite\Excel\Concerns\Exportable;
    use Maatwebsite\Excel\Concerns\FromView;
    use Maatwebsite\Excel\Concerns\ShouldAutoSize;

    /**
     * Class ItemExport
     *
     * @package App\Exports
     */
    class ItemExtraDataExport implements FromView, ShouldAutoSize
    {
        use Exportable;

        /** @var string|null */
        protected $field;
        /** @var Collection|null */
        protected $records;

        /**
         * @return string|null
         */
        public function getField(): ?string
        {
            return $this->field;
        }

        /**
         * @param string|null $field
         *
         * @return ItemExtraDataExport
         */
        public function setField(?string $field): ItemExtraDataExport
        {
            $this->field = $field;
            return $this;
        }

        /**
         * @return Collection|null
         */
        public function getRecords(): ?Collection
        {
            return $this->records;
        }

        /**
         * @param Collection|null $records
         *
         * @return ItemExtraDataExport
         */
        public function setRecords(?Collection $records): ItemExtraDataExport
        {
            $this->records = $records;
            return $this;
        }



        public function view(): View
        {
            return view('tenant.items.exports.items_extra_data', [
                'records' => $this->getRecords(),
                'field' => $this->getField(),
            ]);
        }


    }
