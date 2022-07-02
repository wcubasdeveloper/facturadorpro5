<?php

namespace App\Console\Commands;

// use App\CoreFacturalo\Services\Extras\ValidateCpe2;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\CoreFacturalo\Services\IntegratedQuery\{
    AuthApi,
    ValidateCpe,
};
use App\Models\Tenant\StateType;


class ValidateDocumentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:documents {establishment_id?} {state_type_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consultar el estado de los documentos electrónicos';

    /**
     * Create a new command instance.
     *
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

        $establishment_id = $this->argument('establishment_id');
        $state_type_id = $this->argument('state_type_id');

        if(!$state_type_id) {
            $state_type_id = '01';
        }

        if ($establishment_id) {

            $documents = Document::query()
                                ->where('establishment_id', $establishment_id)
                                ->where('state_type_id', $state_type_id)
                                ->orderBy('series')
                                ->orderBy('number')
                                ->get();

        } else {

            $documents = Document::query()
                                ->where('state_type_id', $state_type_id)
                                ->orderBy('series')
                                ->orderBy('number')
                                ->get();

        }

        $count = 0;
        $this->info('-------------------------------------------------');
        $this->info(Company::query()->first()->name);
        $this->info('----- Documentos:' . $documents->count().' ----- ');

        if($documents->count() > 0){
            
            $auth_api = (new AuthApi())->getToken();
            
            if(!$auth_api['success']) {
                $this->info($auth_api['message']);
            
            }else{

                $access_token = $auth_api['data']['access_token'];
                $state_types = StateType::get();

                foreach ($documents as $document)
                {
                    $count++;
                    
                    $validate_cpe = new ValidateCpe(
                                        $access_token,
                                        $document->company->number,
                                        $document->document_type_id,
                                        $document->series,
                                        $document->number,
                                        $document->date_of_issue,
                                        $document->total
                                    );

                    $response = $validate_cpe->search();

                    if ($response['success']) {

                        $response_description = $response['message'];
                        $response_code = $response['data']['estadoCp'];
                        $response_state_type_id = $response['data']['state_type_id'];
                        
                        $state_type = $state_types->first(function($state) use($response_state_type_id){
                            return $state->id === $response_state_type_id;
                        });

                        $state_type_description = $state_type ? $state_type->description : 'No existe';

                        $message = $count.': '.$document->number_full.' | Código: '.$response_code.' | Mensaje: '.$response_description
                                    .'| Estado Sistema: '.$document->state_type_id.' - '.$document->state_type->description
                                    .' | Estado Sunat: '.$response_state_type_id.' - '.$state_type_description;

                        $this->info($message);
                        
                        if($response_code !== '1') Log::info($message);

                    }
 
                }

            }

        }


        $this->info('-------------------------------------------------');

        return ;
    }
}
