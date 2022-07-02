<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     *
     * Etapas
     * Modules\DocumentaryProcedure\Models\DocumentaryOffice
     *
     *
     * @package Modules\DocumentaryProcedure\Models
     *
     * @property int                                      $id
     * @property string                                   $name
     * @property string|null                              $description
     * @property string|null                              $color
     * @property bool                                     $active
     * @property Carbon|null                              $created_at
     * @property Carbon|null                              $updated_at
     * @property int                                      $days
     * @property int                                      $default
     *
     * @property Collection|DocumentaryProcessesRelFile[] $documentary_processes_rel_files_where_doc_office
     * @property Collection|DocumentaryProcessesRelOff[]  $documentary_processes_rel_offs_where_doc_office *
     * @method static Builder|DocumentaryOffice newModelQuery()
     * @method static Builder|DocumentaryOffice newQuery()
     * @method static Builder|DocumentaryOffice query()
     * @mixin ModelTenant
     */
    class DocumentaryOffice extends ModelTenant {
        protected $table = 'documentary_offices';
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'active'  => 'bool',
            'days'    => 'int',
            'default' => 'int',
        ];

        protected $fillable = [
            'name',
            'description',
            'active',
            'days',
            'color',
            'default',
        ];

        /**
         * @param int $days
         *
         * @return DocumentaryOffice
         */
        public function setDays(int $days = 0)
        : DocumentaryOffice {
            $this->days = (int)$days;
            return $this;
        }

        /**
         * @param string $description
         *
         * @return DocumentaryOffice
         */
        public function setDescription($description)
        : DocumentaryOffice {
            $this->description = (string)$description;
            return $this;
        }

        /**
         * @param string $name
         *
         * @return DocumentaryOffice
         */
        public function setName($name)
        : DocumentaryOffice {
            $this->name = (string)$name;
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
         * @param false $extended
         *
         * @return array
         */
        public function getCollectionData($extended = false, Carbon $today = null,$holidays=[]) {

            if(empty($today)){
                $today = Carbon::now();
            }
            $data = [
                'id'                              => $this->id,
                'name'                            => $this->getName(),
                'print_name'                      => $this->getName(),
                'color'                      => $this->color,
                'description'                     => $this->getDescription(),
                'active'                          => (bool)$this->active,
                'default'                         => (bool)$this->default,
                'days'                            => (int)$this->days,
                'process_rel_files'               => $this->documentary_processes_rel_files_where_doc_office()->get(),
                'process_rel_offices'             => $this->documentary_processes_rel_offs_where_doc_office()->get(),
                'string_days'                     => ($this->getDays() == 1) ? $this->getDays().' Día'
                    : $this->getDays().' Dias',
                'users'                           => RelUserToDocumentaryOffices::where('documentary_office_id',
                                                                                        $this->id)
                                                                                ->where('active', 1)
                                                                                ->get()
                                                                                ->pluck('user_id'),
                'rel_user_to_documentary_offices' =>
                    RelUserToDocumentaryOffices::where('documentary_office_id',
                                                       $this->id)
                                               ->get()
                                               ->transform(function ($row) {
                                                   /** @var RelUserToDocumentaryOffices $row */
                                                   return $row->getCollectionData();
                                               }),
            ];


            $data['selector_name'] = $data['name']." - ".$data['string_days'];
            $data['users_name'] = User::wherein('id', $data['users'])->get()->transform(function ($row) {
                /** @var User @row */
                return $row->getCollectionData();
            });


            $currentDay = 1;
            $total_dias = $this->days;
            $data['start_date'] = $today->format('Y-m-d H:i');
            $days = [];
            while ($currentDay <= $total_dias) {
                if ($today->isWeekend()) {
                    // fin de semana, no hace nada
                    $days[]="Fin de semana ".$today->format('Y-m-d');
                } elseif (in_array($today->format('d-m-Y'),$holidays)) {
                    // Dia festivo, no hace nada
                    $days[] = 'Feriado '.$today->format('Y-m-d');
                } else {
                    ++$currentDay;
                    $days[] = 'Normal '.$today->format('Y-m-d');

                }
                    $today = $today->addDay();
            }
            $data['CalculoDias'] = $days;
            $data['carbon_end_date'] = $today;
            $data['end_date'] = $today->format('Y-m-d H:i');
            $data['print_name'].=" (Fin estimado) ".$data['end_date'];
            if ($extended === true) {
                $data['documentary_files_archives'] =
                    DocumentaryFilesArchives::where('documentary_office_id',
                                                    $this->id)
                                            ->get()
                                            ->transform(function ($row) {
                                                /** @var DocumentaryFilesArchives $row */
                                                return $row->getCollectionData();
                                            });



            }

            return $data;
        }

        /**
         * @return string
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @return string
         */
        public function getDescription() {
            return (string)$this->description;
        }

        /**
         * Relacion entre procesos y archivos
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documentary_processes_rel_files_where_doc_office() {
            return $this->hasMany(DocumentaryProcessesRelFile::class, 'doc_office_id');
        }

        /**
         * Relacion entre procesos y etapas
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documentary_processes_rel_offs_where_doc_office() {
            return $this->hasMany(DocumentaryProcessesRelOff::class, 'doc_offices_id');
        }

        /**
         * @return int
         */
        public function getDays()
        : int {
            return (int)$this->days;
        }

    }
