<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryFilesArchives
     *
     * @method static Builder|DocumentaryFilesArchives newModelQuery()
     * @method static Builder|DocumentaryFilesArchives newQuery()
     * @method static Builder|DocumentaryFilesArchives query()
     * @mixin \Eloquent
     */
    class DocumentaryFilesArchives extends ModelTenant {
        use UsesTenantConnection;
        protected $table = 'documentary_files_archives';

        protected $fillable = [
            'user_id',
            'documentary_file_id',
            'documentary_office_id',
            'observation',
            'documentary_guides_number_id',
            'attached_file',
        ];

        protected static function boot() {
            parent::boot();
            static::creating(function (DocumentaryFilesArchives $model) {
                if (auth() && auth()->user() && auth()->user()->id) {
                    $model->user_id = auth()->user()->id;
                } else {
                    $model->user_id = 0;
                }


            });
        }

        /**
         * @return int
         */
        public function getUserId()
         {
            return $this->user_id;
        }

        /**
         * @param int $user_id
         *
         * @return DocumentaryFilesArchives
         */
        public function setUserId($user_id)
        : DocumentaryFilesArchives {
            $this->user_id = $user_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryFileId()
         {
            return $this->documentary_file_id;
        }

        /**
         * @param int $documentary_file_id
         *
         * @return DocumentaryFilesArchives
         */
        public function setDocumentaryFileId($documentary_file_id)
        : DocumentaryFilesArchives {
            $this->documentary_file_id = $documentary_file_id;
            return $this;
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
         * @return DocumentaryFilesArchives
         */
        public function setDocumentaryOfficeId($documentary_office_id)
        : DocumentaryFilesArchives {
            $this->documentary_office_id = $documentary_office_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getObservation()
         {
            return $this->observation;
        }

        /**
         * @param string $observation
         *
         * @return DocumentaryFilesArchives
         */
        public function setObservation( $observation)
        : DocumentaryFilesArchives {
            $this->observation = $observation;
            return $this;
        }

        /**
         * @return string
         */
        public function getAttachedFile()
         {
            return $this->attached_file;
        }

        /**
         * @param string $attached_file
         *
         * @return DocumentaryFilesArchives
         */
        public function setAttachedFile( $attached_file)
        : DocumentaryFilesArchives {
            $this->attached_file = $attached_file;
            return $this;
        }
        public function getPublicUrl(){
            return route('documentaryprocedure.download.file',['id'=>$this->id]);
        }
        public function getPublicName(){
            $splitName = explode('/',$this->attached_file);
            $splitName = end($splitName);
            return $splitName;
        }

        public function getCollectionData() {
            $data = $this->toArray();
            $data['public_name'] = $this->getPublicName();
            $data['user'] = User::find($this->user_id);
            $data['public_url'] = $this->getPublicUrl();
            $data['documentary_office'] = DocumentaryOffice::find($this->documentary_office_id);
            $data['documentary_file'] = DocumentaryFile::find($this->documentary_file_id);
            return $data;
        }

        public static function saveFile(UploadedFile $file){
            // $file->storePubliclyAs()
                   $ext = $file->getClientOriginalExtension();
            $filenameOriginal = str_replace('.'.$ext, '', $file->getClientOriginalName());
            $name = $filenameOriginal.'-'.time().'.'.$ext;
            $path = '/uploads/files/';
            // $path = config('filesystems.disks.public.root')

            $fullpath = $path.$name;
            $file->storeAs($path, $name);
            return $fullpath;

        }



        public function documentary_guides_number()
        {
            return $this->belongsTo(DocumentaryGuidesNumber::class);;
        }
    }
