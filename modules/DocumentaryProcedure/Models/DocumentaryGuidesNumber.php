<?php


    namespace Modules\DocumentaryProcedure\Models;


    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasOne;


    /**
     * Class DocumentaryGuidesNumber
     *
     * @property int                    $id
     * @property int|null               $doc_file_id
     * @property int|null               $doc_office_id
     * @property string|null            $guide
     * @property string|null            $origin
     * @property Carbon|null            $created_at
     * @property Carbon|null            $updated_at
     * @property Carbon|null            $date_of_due
     * @property string|null            $observation
     * @property string|null            $description
     * @property Carbon|null            $date_take
     * @property Carbon|null            $date_end
     * @property int|null               $documentary_guides_number_status_id
     * @property int                    $user_id
     * @property int                    $total_day
     * @package Modules\DocumentaryProcedure\Models
     * @property-read DocumentaryFile   $doc_file
     * @property-read DocumentaryOffice $doc_office
     * @method static Builder|DocumentaryGuidesNumber newModelQuery()
     * @method static Builder|DocumentaryGuidesNumber newQuery()
     * @method static Builder|DocumentaryGuidesNumber query()
     * @mixin Eloquent
     */
    class DocumentaryGuidesNumber extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'documentary_guides_number';
        protected $perPage = 25;

        protected $casts = [
            'doc_file_id' => 'int',
            'doc_office_id' => 'int',
            'documentary_guides_number_status_id' => 'int',
            'user_id' => 'int',
            'total_day' => 'int',
        ];

        protected $fillable = [
            'doc_file_id',
            'doc_office_id',
            'guide',
            'origin',
            'date_of_due',
            'observation',
            'description',
            'date_take',
            'date_end',
            'documentary_guides_number_status_id',
            'user_id',
            'total_day',
        ];

        protected static function boot()
        {
            parent::boot();
            static::saved(function (self $model) {
                self::updateDocumentary($model);

            });
            static::updated(function(self$model){
                self::updateDocumentary($model);
            });

        }

        public  static  function  updateDocumentary(self $model){

            $doc_file_id = (int)$model->doc_file_id;
            // $files =DocumentaryFile::where('id',$doc_file_id)->last();
            $steps =self::where('doc_file_id',$doc_file_id)->orderBy('created_at','desc')->first();
            $doc_file_id = DocumentaryFile::find($doc_file_id);
            if(!empty($doc_file_id) && !empty($steps)) {
                $doc_file_id->documentary_guides_number_status_id = $steps->doc_office_id;
                $doc_file_id->date_end = $steps->date_end;
                $doc_file_id->push();
            }

            return $model;
        }


        /**
         * @return BelongsTo
         */
        public function doc_file()
        {
            return $this->belongsTo(DocumentaryFile::class, 'doc_file_id');
        }

        /**
         * @return BelongsTo
         */
        public function doc_office()
        {
            return $this->belongsTo(DocumentaryOffice::class, 'doc_office_id');
        }

        public function documentary_guides_number_status()
        {
            return $this->belongsTo(DocumentaryGuidesNumberStatus::class);;
        }

        /**
         * @return string|null
         */
        public function getGuide(): ?string
        {
            return $this->guide;
        }

        /**
         * @param string|null $guide
         *
         * @return DocumentaryGuidesNumber
         */
        public function setGuide(?string $guide): DocumentaryGuidesNumber
        {
            $this->guide = $guide;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getOrigin(): ?string
        {
            return $this->origin;
        }

        /**
         * @param string|null $origin
         *
         * @return DocumentaryGuidesNumber
         */
        public function setOrigin(?string $origin): DocumentaryGuidesNumber
        {
            $this->origin = $origin;
            return $this;
        }

        public function documentary_file_archive(){
            $this->hasMany(DocumentaryFilesArchives::class,'documentary_guides_number_id','id');
        }


        public function getCollectionData(){


            $this->files = DocumentaryFilesArchives::where('documentary_guides_number_id',$this->id)->get()->transform( function (DocumentaryFilesArchives $row){
                return $row->getCollectionData();
            });
            $this->office = $this->doc_office ;
            $class = 'badge bg-secondary text-white font-14 ';


            if(!empty( $this->date_end )){
                $now = Carbon::now();
                $parse = Carbon::createFromFormat('Y-m-d H:i:s',$this->date_end);
                if($now > $parse){
                    $class .= "bg-danger";
                }else{
                    $class .= "bg-success";
                }
            }
            $this->class = $class;
            $this->by_day = true;
            return $this;

        }
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }


    }
