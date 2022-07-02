<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * Class DocumentaryFile
     *
     * @property int                                     $id
     * @property int|null                                $user_id
     * @property int|null                                $documentary_document_id
     * @property int                                     $documentary_process_id
     * @property string|null                             $number
     * @property int                                     $year
     * @property string                                  $invoice
     * @property string                                  $date_register
     * @property string                                  $time_register
     * @property int                                     $person_id
     * @property int                                     $documentary_guides_number_status_id
     * @property string|null                             $sender
     * @property string|null                             $subject
     * @property string|null                             $attached_file
     * @property string|null                             $observation
     * @property string                                  $status
     * @property int                                     $documentary_office_id
     * @property int                                     $establishment_id
     * @property Carbon|null                             $created_at
     * @property Carbon|null                             $updated_at
     * @property string|null                             $requirements
     * @property bool                                    $is_simplify
     * @property bool                                    $is_completed
     * @property bool                                    $is_archive
     * @property DocumentaryProcess                      $documentary_process
     * @property Person                                  $person
     * @property Carbon|null            $date_end
     * @property User|null                               $user
     * @method static Builder|DocumentaryFile newModelQuery()
     * @method static Builder|DocumentaryFile newQuery()
     * @method static Builder|DocumentaryFile query()
     * @mixin Eloquent
     * @package Modules\DocumentaryProcedure\Models
     * @property-read mixed                              $active
     * @property-read Collection|DocumentaryFileOffice[] $offices
     * @property-read int|null                           $offices_count
     * @method static Builder|DocumentaryFile withOutSimplify()
     * @method static Builder|DocumentaryFile withSimplify()
     */
    class DocumentaryFile extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'documentary_files';
        protected $fillable = [
            'user_id',
            'documentary_document_id',
            'documentary_guides_number_status_id',
            'documentary_process_id',
            'number',
            'year',
            'invoice',
            'date_register',
            'time_register',
            'person_id',
            'sender',
            'subject',
            'attached_file',
            'observation',
            'status',
            'documentary_office_id',
            'requirements',
            'date_end',
            'is_simplify',
            'establishment_id',
            'is_archive',
            'is_completed',
        ];
        protected $casts = [
            'user_id' => 'int',
            'documentary_guides_number_status_id' => 'int',
            'documentary_document_id' => 'int',
            'documentary_process_id' => 'int',
            'establishment_id' => 'int',
            'year' => 'int',
            'person_id' => 'int',
            'documentary_office_id' => 'int',
            'is_simplify' => 'bool',
            'is_archive' => 'bool',
            'is_completed' => 'bool',
        ];

        protected static function boot()
        {
            parent::boot();
            static::saving(function (self $model) {
                if (empty($model->sender)) {
                    /** @var Person $person */
                    $person = $model->person;
                    $model->sender = [
                        "name" => "",
                        "address" => "",
                        "number" => "",
                        "identity_document_type_id" => ""
                    ];
                    if (!empty($person)) {
                        $model->sender = [
                            "name" => $person->name,
                            "address" => $person->address,
                            "number" => $person->number,
                            "identity_document_type_id" => $person->identity_document_type_id,
                        ];
                    }
                }

                /*
                $guides =  $model->documentary_guide_number;
                if(!empty($guides)){
                    $last = $guides->last();
                    $model->documentary_guides_number_status_id = $last->doc_office_id;
                }
                */


            });
            static::creating(function(self$model){
                if(empty($model->establishment_id) && !empty(\Auth::user())){
                    $model->establishment_id = \Auth::user()->establishment_id;
                }
            });

        }

        /**
         * @return int
         */
        public function getDocumentaryOfficeId()
        {
            return $this->documentary_office_id;
        }

        /**
         * @param int $documentary_office_id
         *
         * @return DocumentaryFile
         */
        public function setDocumentaryOfficeId($documentary_office_id): DocumentaryFile
        {
            $this->documentary_office_id = $documentary_office_id;
            return $this;
        }

        public function getActiveAttribute($value)
        {
            return $value ? true : false;
        }

        public function getSenderAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setSenderAttribute($value)
        {
            $this->attributes['sender'] = (is_null($value)) ? null : json_encode($value);
            return $this;
        }

        /**
         * @return HasMany
         */
        public function offices()
        {
            return $this->hasMany(DocumentaryFileOffice::class, 'documentary_file_id');
        }

        /**
         * @return array
         */
        public function getCollectionData($holiday = [])
        {
            $data = $this->toArray();
            $data['is_completed']= (bool)$this->is_completed;
            $person = Person::find($this->person_id);
            $documentary_file_office = DocumentaryOffice::find($this->documentary_office_id);
            if (empty($documentary_file_office)) $documentary_file_office = new DocumentaryOffice();
            $documentary_process = DocumentaryProcess::find($this->documentary_process_id);
            if (empty($documentary_process)) $documentary_process = new DocumentaryProcess();
            /*
                              $documentary_file_archives = DocumentaryFilesArchives::where('documentary_file_id', $this->id)->get();

                              */
            $guides =  $this->documentary_guide_number;
            // $data['guides'] =$guides;
            $data['guides'] =$guides->transform(function (DocumentaryGuidesNumber $row){
                return $row->getCollectionData();
            });
            $lastGuide = $guides->last();
            // $lastGuide->office = [];}
            if($lastGuide === null) {
                $lastGuide = new DocumentaryGuidesNumber();
            }
            // $lastGuide->office = $lastGuide->doc_office ;
            $lastGuide = $lastGuide->getCollectionData();

            $data['datetime_register'] = $this->date_register." - ".$this->time_register;
            $data['last_guide'] = $lastGuide;
            $data['last_guide_status'] = $lastGuide->documentary_guides_number_status ?? null;
            $data['documentary_office'] = $documentary_file_office->getCollectionData();
            $data['documentary_process'] = $documentary_process->getCollectionData($holiday);
            $data['documentary_process_id'] = (int)$this->documentary_process_id;

            /*


                     $data['documentary_file_archives'] = $documentary_file_archives->transform(function (DocumentaryFilesArchives $row) {
                         return $row->getCollectionData();
                     });
                     $lastComplete = [];
                     $data['observations'] = DocumentaryObservation::where('doc_file_id', $this->id)->get()
                         ->transform(function (DocumentaryObservation $row) {
                             return $row->getCollectionData();
                         });

                     $nextStep = $this->documentary_office_id;
                     $data['documentary_file_offices'] =
                         DocumentaryFileOffice::where('documentary_file_id', $this->id)
                             ->get()
                             ->transform(function (DocumentaryFileOffice $row) use (&$lastComplete, $nextStep) {
                                 $data = $row->getCollectionData();
                                 if (count($lastComplete) == 0) {
                                     // se guarda el primer proceso
                                     $lastComplete = $data;
                                 }
                                 if ($row->documentary_office_id == $nextStep) {
                                     $lastComplete = $data;
                                 }

                                 return $data;
                             });
                     */
            $data['person'] = $person->getCollectionData();
            // $data['last_complete'] = $lastComplete;
            $requirement_array = [];
            foreach ($this->getRequirements() as $requirement) {
                /* para el-checkbox se requiere el id del elemento => true para estar seleccionado*/
                $requirement_array[$requirement] = true;
            }
            $data['requirements_id'] = $requirement_array;
            return $data;

        }

        /**
         * @return false|int[]
         */
        public function getRequirements(): ?array
        {

            return self::makeArray($this->requirements);
        }

        /**
         * @param string|null $text
         *
         * @return false|int[]
         */
        protected static function makeArray(?string $text = '')
        {
            return array_map('intval', explode(',', $text));

        }

        /**
         * @return Collection|DocumentaryOffice[]
         */
        public function getOffices()
        {
            return $this->offices;
        }

        /**
         * @param Collection|DocumentaryOffice[] $offices
         *
         * @return DocumentaryFile
         */
        public function setOffices($offices)
        {
            $this->offices = $offices;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getOfficesCount(): ?int
        {
            return $this->offices_count;
        }

        /**
         * @param int|null $offices_count
         *
         * @return DocumentaryFile
         */
        public function setOfficesCount(?int $offices_count): DocumentaryFile
        {
            $this->offices_count = $offices_count;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getDocumentaryDocumentId()
        {
            return $this->documentary_document_id;
        }

        /**
         * @param mixed $documentary_document_id
         *
         * @return DocumentaryFile
         */
        public function setDocumentaryDocumentId($documentary_document_id)
        {
            $this->documentary_document_id = $documentary_document_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getDocumentaryProcessId()
        {
            return $this->documentary_process_id;
        }

        /**
         * @param mixed $documentary_process_id
         *
         * @return DocumentaryFile
         */
        public function setDocumentaryProcessId($documentary_process_id)
        {
            $this->documentary_process_id = $documentary_process_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getNumber()
        {
            return $this->number;
        }

        /**
         * @param mixed $number
         *
         * @return DocumentaryFile
         */
        public function setNumber($number)
        {
            $this->number = $number;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getYear()
        {
            return $this->year;
        }

        /**
         * @param mixed $year
         *
         * @return DocumentaryFile
         */
        public function setYear($year)
        {
            $this->year = $year;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getInvoice()
        {
            return $this->invoice;
        }

        /**
         * @param mixed $invoice
         *
         * @return DocumentaryFile
         */
        public function setInvoice($invoice)
        {
            $this->invoice = $invoice;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getDateRegister()
        {
            return $this->date_register;
        }

        /**
         * @param mixed $date_register
         *
         * @return DocumentaryFile
         */
        public function setDateRegister($date_register)
        {
            $this->date_register = $date_register;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getTimeRegister()
        {
            return $this->time_register;
        }

        /**
         * @param mixed $time_register
         *
         * @return DocumentaryFile
         */
        public function setTimeRegister($time_register)
        {
            $this->time_register = $time_register;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getPersonId()
        {
            return $this->person_id;
        }

        /**
         * @param mixed $person_id
         *
         * @return DocumentaryFile
         */
        public function setPersonId($person_id)
        {
            $this->person_id = $person_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getSubject()
        {
            return $this->subject;
        }

        /**
         * @param mixed $subject
         *
         * @return DocumentaryFile
         */
        public function setSubject($subject)
        {
            $this->subject = $subject;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getAttachedFile()
        {
            return $this->attached_file;
        }

        /**
         * @param mixed $attached_file
         *
         * @return DocumentaryFile
         */
        public function setAttachedFile($attached_file)
        {
            $this->attached_file = $attached_file;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getObservation()
        {
            return $this->observation;
        }

        /**
         * @param mixed $observation
         *
         * @return DocumentaryFile
         */
        public function setObservation($observation)
        {
            if (!empty($observation)) {
                $this->observation = $observation;
            }
            return $this;
        }

        /**
         * @param array|null $requirements
         *
         * @return DocumentaryFile
         */
        public function setRequirements(?array $requirements = []): DocumentaryFile
        {
            $this->requirements = self::splitArray($requirements);

            return $this;
        }

        /**
         * @param array|null $array
         *
         * @return string
         */
        protected static function splitArray(?array $array = [])
        {
            return implode(',', $array);

        }


        /**
         * @return BelongsTo
         */
        public function documentary_process()
        {
            return $this->belongsTo(DocumentaryProcess::class);
        }

        /**
         * @return BelongsTo
         */
        public function person()
        {
            return $this->belongsTo(Person::class);
        }

        /**
         * @return BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @param Builder $query
         *
         * @return Builder
         */
        public function scopeWithSimplify(Builder $query)
        {
            return $query->where('is_simplify', 1);
        }

        /**
         * @param Builder $query
         *
         * @return Builder
         */
        public function scopeWithOutSimplify(Builder $query)
        {
            return $query->where('is_simplify', 0);
        }

        /**
         * @return bool
         */
        public function isIsSimplify(): bool
        {
            return (bool)$this->is_simplify;
        }


        /**
         * @param bool $is_simplify
         *
         * @return DocumentaryFile
         */
        public function setIsSimplify(bool $is_simplify): DocumentaryFile
        {
            $this->is_simplify = (bool)$is_simplify;
            return $this;
        }

        /**
         * @return HasMany
         */
        public function documentary_guide_number()
        {
            return $this->hasMany(DocumentaryGuidesNumber::class, 'doc_file_id');
        }

        public function  setArchive( ?bool $archive = false){
            $this->is_archive = (bool)$archive;
            return $this;
        }
        public function  setComplete( ?bool $complete = false){
            $this->is_completed = (bool)$complete;
            return $this;
        }

        public function scopeWithArchive(Builder $query){
            return $query->where('is_archive',1);
        }
        public function scopeWithOutArchive(Builder $query){
            return $query->where('is_archive',0);
        }
        public function  scopeExpired($query){
            return $query->where('date_end',"<",Carbon::now());
        }

    }
