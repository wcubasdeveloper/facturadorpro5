<?php

    namespace App\Console\Commands;

    use App\Models\Tenant\Document;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\DB;
    use Modules\Account\Models\AccountingLedger;
    use Modules\Account\Models\AccountingLedgerTask;

    class AccountLedgerFillCommand extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'account_ledger:fill';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Ejecuta el proceso de llenado de la tabla accounting_ledger en forma asincrona para no saturar el servidor';

        /**
         * Create a new command instance.
         *
         * @return void
         */
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Execute the console command.
         *
         * @return mixed
         */
        public function handle()
        {
            $minutes = 60;
            $now = Carbon::now()->addMinutes($minutes);
            $this->info('Se ha iniciado account_ledger:fill');
//            $months = $this->getDatesToReport();
            $months = [];
            DB::connection('tenant')->transaction(function () use ( &$months) {
// * @var Collection $documents
//
                /**
                 * @var Carbon     $documents_min
                 * @var Carbon     $documents_max
                 */
                $documents = Document::query()
                    ->select('date_of_issue')
                    ->groupby('date_of_issue')->get();

                $documents_min = $documents->min('date_of_issue');
                $documents_max = $documents->max('date_of_issue');
                do {
                    if(!empty($documents_min)) {
                        $d = $documents_min->firstOfMonth();
                        $f = $d->format('Y-m');
                        $months[$f] = Carbon::createFromFormat('Y-m', $f)->firstOfMonth()->setTime(0, 0, 0);
                    }
                } while ($documents_min->addMonth() <= $documents_max);
            });

            $numberRecorsToSave = 3;

            DB::connection('tenant')->transaction(function () use ($now, $months, $numberRecorsToSave) {
                /** @var Carbon $month */
                $index = 0;
                foreach ($months as $month) {
                    if ($index >= $numberRecorsToSave) break;
                    $user = User::where('type', 'admin')->orderBy('id', 'asc')->first();
                    auth()->login($user);
                    $monthNumber = $month->format('m');
                    $yearNumber = $month->format('Y');
                    $task = AccountingLedgerTask::where([
                        'month' => $monthNumber,
                        'year' => $yearNumber,
                    ])
                        ->where('updated_at', '>', $now)
                        ->first();
                    if ($task == null) {
                        $task = AccountingLedgerTask::where([
                            'month' => $monthNumber,
                            'year' => $yearNumber,
                        ])->first();
                        if (empty($task)) {
                            $task = new AccountingLedgerTask();
                        }
                        $records = AccountingLedger::saveData($month);
                        $task->setMonth($monthNumber)
                            ->setYear($yearNumber)
                            ->push();
                        $this->info('Se ha actualizado el mes '.$monthNumber." para el año $yearNumber");
                        $index +=1;
                    }
                }
            });
            $this->info('Se ha finalizado el comando');


        }

        /**
         * Devuelve el rango de meses para los documentos
         *
         * @return array
         */
        protected function getDatesToReport()
        {

            return DB::connection('tenant')->transaction(function () {
                /**
                 * @var Collection $documents
                 * @var Carbon     $documents_min
                 * @var Carbon     $documents_max
                 */
                $documents = Document::query()
                    ->select('date_of_issue')
                    ->groupby('date_of_issue')->get();

                $documents_min = $documents->min('date_of_issue');
                $documents_max = $documents->max('date_of_issue');
                $months = [];
                do {
                    if(!empty($documents_min)) {
                        $d = $documents_min->firstOfMonth();
                        $f = $d->format('Y-m');
                        $months[$f] = Carbon::createFromFormat('Y-m', $f)->firstOfMonth()->setTime(0, 0, 0);
                    }
                } while ($documents_min->addMonth() <= $documents_max);
                return $months;
            });
        }

        public function saveRecord()
        {

        }
    }
