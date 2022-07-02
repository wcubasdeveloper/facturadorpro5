<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\System\HistoryResource;
use App\Http\Controllers\System\StatusController;
use Carbon\Carbon;

class StatusServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se ejecutara por hora guardando estado de cpu y memoria (windows/linux)';

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

        $this->info('The command was started');

        // se repite el guardado varias veces
        $last = HistoryResource::orderBy('created_at', 'desc')->first();
        $now = Carbon::now();
        // valido si ya hay un registro en este minuto
        if($last && $now->diffInMinutes($last->created_at))
        {
            $this->saveRecord();
        } else {
            if($last == null){
                $this->saveRecord();
            }
        }



        $this->info("The command is finished");
    }

    public function saveRecord()
    {
        $statusController = new StatusController();
        $memory = $statusController->memory(false);
        $cpu = $statusController->cpu();

        $history = new HistoryResource();
        $history->cpu_percent = $cpu['cpu'];
        $history->memory_total = $memory['total'];
        $history->memory_free = $memory['free'];
        $history->memory_used = $memory['used'];
        $history->save();
        sleep(15);
    }
}
