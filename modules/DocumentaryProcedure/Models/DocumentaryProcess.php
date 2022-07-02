<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Modules\DocumentaryProcedure\Models\DocumentaryFile as Expediente;
    use Modules\DocumentaryProcedure\Models\DocumentaryProcessesRelFile as TramiteRelExpediente;
    use Modules\DocumentaryProcedure\Models\DocumentaryProcessesRelOff as TramiteRelEtapa;
    use Modules\DocumentaryProcedure\Models\DocumentaryProcessesRelReq as TramiteRelRequisito;


    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryProcess
     *
     * @method static Builder|DocumentaryProcess newModelQuery()
     * @method static Builder|DocumentaryProcess newQuery()
     * @method static Builder|DocumentaryProcess query()
     * @mixin \Eloquent
     */
    class DocumentaryProcess extends ModelTenant {

        use UsesTenantConnection;

        protected $perPage = 25;
        protected $table = 'documentary_processes';

        protected $casts = [
            'price'  => 'float',
            'active' => 'bool',
        ];
        protected $fillable = [
            'description',
            'active',
            'price',
            'name',
            'documentary_offices',
            'documentary_offices_order',
        ];

        /**
         * Retorna la relacion con expedientes
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documentary_files() {
            return $this->hasMany(Expediente::class);
        }

        /**
         * Retorna relacion con los procesos y expedientes
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documentary_processes_rel_files_where_doc_process() {
            return $this->hasMany(TramiteRelExpediente::class, 'doc_processes_id');
        }

        /**
         * Retorna la relacion de procesos y relaciones
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documentary_processes_rel_reqs_where_doc_process() {
            return $this->hasMany(TramiteRelRequisito::class, 'doc_processes_id');
        }


        /**
         * @return array
         */
        public function getCollectionData($holidays = []) {
            Carbon::setWeekendDays([
                                       Carbon::SATURDAY,
                                       Carbon::SUNDAY,
                                   ]);
            $today = Carbon::now();
            // $holyday = [];
            $data = [
                'id'            => $this->id,
                'description'   => $this->getDescription(),
                'price'         => $this->getPrice(),
                'name'          => $this->getName(),
                'name_price'    => $this->getName().' - S/ '.$this->priceWithDecimal(),
                'active'        => (bool)$this->active,
                'disable'       => !((bool)$this->active),
                'stages'        => $this->getDocumentaryOffices(),
                'full_stages'   => DocumentaryOffice::wherein('id', $this->getDocumentaryOffices())->get()
                                                    ->transform(function ($row) use(&$today,$holidays) {
                                                        /** @var DocumentaryOffice $row */
                                                        $data = $row->getCollectionData(false, $today,$holidays);
                                                        $today = $data['carbon_end_date'];
                                                        return $data;
                                                    }),
                'stages_order'  => $this->getDocumentaryOfficesOrder(),
                'process_stage' => $this->documentary_processes_rel_offs_where_doc_process()->get()
                                        ->transform(function ($row)   {
                                            /** @var TramiteRelEtapa $row */
                                           return  $row->getCollectionData();

                                        }),
            ];


            $data['end_date'] = $today->format('Y-m-d H:i');
            //$data['name_price'].=" (Fecha de entrega estimada) ".$today->format('d-m-Y H:i');

            $req = TramiteRelRequisito::where('doc_processes_id', $this->id)->get();
            $data['requirements'] = $req->transform(function (TramiteRelRequisito $row) {
                    return $row->getCollectionData();
                });
            $data['requirements_id'] = $req->pluck('requirement_id');

            $data['documentary_terms']=[];
            $data['documentary_terms'][]=['term_name'=>'Capital hasta S/5,000.00'];
            $data['documentary_terms'][]=['term_name'=>'2 Socios'];
            $data['documentary_terms'][]=['term_name'=>'Hasta 4 rubros o actividades'];
            $data['documentary_terms'][]=['term_name'=>'1 Gerente'];
            return $data;
        }

        /**
         * @return string
         */
        public function getDescription() {
            return $this->description;
        }

        /**
         * @return float
         */
        public function getPrice() {
            return $this->price;
        }

        /**
         * @return string
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param int $decimal
         *
         * @return string
         */
        public function priceWithDecimal($decimal = 2) {
            return number_format($this->price, $decimal, '.', '');
        }

        /**
         * @return false|int[]
         */
        public function getDocumentaryOffices()
        : ?array {

            return self::makeArray($this->documentary_offices);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|mixed|\Modules\DocumentaryProcedure\Models\DocumentaryOffice[]
         */
        public function getStages(){
            return DocumentaryOffice::where('id',$this->documentary_offices)->get();
        }
        /**
         * @param string|null $text
         *
         * @return false|int[]
         */
        protected static function makeArray(?string $text = '') {
            return array_map('intval', explode(',', $text));

        }

        /**
         * @return false|int[]
         */
        public function getDocumentaryOfficesOrder()
        : ?array {
            return self::makeArray($this->documentary_offices_order);
        }

        /**
         * Retorna la relacion de procesos y etapas
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documentary_processes_rel_offs_where_doc_process() {
            return $this->hasMany(TramiteRelEtapa::class, 'doc_processes_id');
        }

        /**
         * @param string|null $description
         *
         * @return DocumentaryProcess
         */
        public function setDescription($description = '')
        : DocumentaryProcess {
            $this->description = $description;
            return $this;
        }

        /**
         * @param float $price
         *
         * @return DocumentaryProcess
         */
        public function setPrice(float $price = 0)
        : DocumentaryProcess {
            $this->price = (float)$price;
            return $this;
        }

        /**
         * @param string|null $name
         *
         * @return DocumentaryProcess
         */
        public function setName($name = '')
        : DocumentaryProcess {
            $this->name = $name;
            return $this;
        }

        /**
         * @param $value
         *
         * @return bool
         */
        public function getActiveAttribute($value) {
            return $value ? true : false;
        }

        /**
         * @param array|null $documentary_offices
         *
         * @return DocumentaryProcess
         */
        public function setDocumentaryOffices(?array $documentary_offices = [])
        : DocumentaryProcess {
            $this->documentary_offices = self::splitArray($documentary_offices);

            return $this;
        }

        /**
         * @param array|null $array
         *
         * @return string
         */
        protected static function splitArray(?array $array = []) {
            if(empty($array))  $array = [];
            return implode(',', $array);

        }

        /**
         * @param array|null $documentary_offices_order
         *
         * @return DocumentaryProcess
         */
        public function setDocumentaryOfficesOrder(?array $documentary_offices_order = [])
        : DocumentaryProcess {
            $this->documentary_offices_order = self::splitArray($documentary_offices_order);

            return $this;
        }

        /**
         * @param bool $active
         *
         * @return DocumentaryProcess
         */
        public function setActive(bool $active)
        : DocumentaryProcess {
            $this->active = (bool)$active;
            return $this;
        }

        /**
         * @return bool
         */
        public function isActive()
        : bool {
            return (bool)$this->active;
        }


    }
