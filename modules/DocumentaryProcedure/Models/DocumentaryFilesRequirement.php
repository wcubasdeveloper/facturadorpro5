<?php


    namespace Modules\DocumentaryProcedure\Models;


    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class DocumentaryFilesRequirement
     *
     * @property int                                     $id
     * @property string|null                             $name
     * @property bool                                    $file
     * @property Carbon|null                             $created_at
     * @property Carbon|null                             $updated_at
     *
     * @property Collection|DocumentaryProcessesRelReq[] $documentary_processes_rel_reqs_where_doc_files_requirement
     *
     * @package App\Models\Tenant\ModelTenant
     * @mixin  \Eloquent
     */
    class DocumentaryFilesRequirement extends ModelTenant {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'file' => 'bool',
        ];

        protected $fillable = [
            'name',
            'file',
        ];

        /**
         * Relaciona los tramites con los requerimientos
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documentary_processes_rel_reqs_where_doc_files_requirement() {
            return $this->hasMany(DocumentaryProcessesRelReq::class, 'doc_files_requirements_id');
        }

        /**
         * @param string|null $name
         *
         * @return DocumentaryFilesRequirement
         */
        public function setName(?string $name)
        : DocumentaryFilesRequirement {
            $this->name = $name;
            return $this;
        }

        /**
         * @param bool $file
         *
         * @return DocumentaryFilesRequirement
         */
        public function setFile(bool $file = false)
        : DocumentaryFilesRequirement {
            $this->file = (bool)$file;
            return $this;
        }

        /**
         * @return array
         */
        public function getCollectionData() {
            $data = [
                'id'   => $this->id,
                'name' => $this->getName(),
                'file' => $this->getFile(),
            ];
            return $data;
        }

        /**
         * @return string|null
         */
        public function getName()
        : ?string {
            return $this->name;
        }

        /**
         * @return bool
         */
        public function getFile()
        : bool {
            return (bool)$this->file;
        }


    }
