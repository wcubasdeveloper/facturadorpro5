<?php

    namespace Modules\Account\Exports;

    use Illuminate\Contracts\View\View;
    use Maatwebsite\Excel\Concerns\Exportable;
    use Maatwebsite\Excel\Concerns\FromView;
    use Maatwebsite\Excel\Concerns\ShouldAutoSize;

    /**
     * Class LedgerAccountExcelExport
     *
     * @package Modules\Account\Exports
     */
    class LedgerAccountExcelExport implements FromView,ShouldAutoSize
    {
        use Exportable;

        /** @var array */
        protected $records;
        /** @var string|null */
        protected $dateReport;

        public function view(): View
        {
            $records = $this->getRecords();
            $dateReport = $this->getDateReport();
            // $ledgerAccountExcelExport->setAutoSize(true);

            return view('account::accounting_ledger.templates.excel', compact('records', 'dateReport'));

        }

        /**
         * @return array
         */
        public function getRecords(): array
        {
            return $this->records;
        }

        /**
         * @param array $records
         *
         * @return LedgerAccountExcelExport
         */
        public function setRecords(array $records): LedgerAccountExcelExport
        {
            $this->records = $records;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDateReport(): ?string
        {
            return $this->dateReport;
        }

        /**
         * @param string|null $dateReport
         *
         * @return LedgerAccountExcelExport
         */
        public function setDateReport(?string $dateReport): LedgerAccountExcelExport
        {
            $this->dateReport = $dateReport;
            return $this;
        }
    }
