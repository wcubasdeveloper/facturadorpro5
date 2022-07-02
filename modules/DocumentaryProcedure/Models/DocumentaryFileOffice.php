<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Modules\DocumentaryProcedure\Models\DocumentaryFileOffice
     *
     * @method static Builder|DocumentaryFileOffice newModelQuery()
     * @method static Builder|DocumentaryFileOffice newQuery()
     * @method static Builder|DocumentaryFileOffice query()
     * @mixin \Eloquent
     */
    class DocumentaryFileOffice extends ModelTenant {
        protected $table = 'documentary_file_offices';
        use UsesTenantConnection;

        protected $fillable = [
            'documentary_file_id',
            'documentary_office_id',
            'documentary_action_id',
            'observation',
            'status',
            'office_name',
            'process_name',
            'documentary_process_id',
            'complete',
            'start_date',
            'end_date',
            'days',
        ];


        /**
         * @return int
         */
        public function getDocumentaryFileId() {
            return $this->documentary_file_id;
        }

        /**
         * @param int $documentary_file_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryFileId($documentary_file_id = 0)
        : DocumentaryFileOffice {
            $this->documentary_file_id = (int)$documentary_file_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryOfficeId() {
            return $this->documentary_office_id;
        }

        /**
         * @param int $documentary_office_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryOfficeId($documentary_office_id = 0)
        : DocumentaryFileOffice {
            $this->documentary_office_id = (int)$documentary_office_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getDocumentaryActionId() {
            return $this->documentary_action_id;
        }

        /**
         * @param int $documentary_action_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryActionId($documentary_action_id = 0)
        : DocumentaryFileOffice {
            $this->documentary_action_id = (int)$documentary_action_id;
            return $this;
        }

        /**
         * @return bool
         */
        public function getObservation() {
            return (bool)$this->observation;
        }

        /**
         * @param bool $observation
         *
         * @return DocumentaryFileOffice
         */
        public function setObservation($observation = false)
        : DocumentaryFileOffice {
            $this->observation = (bool)$observation;
            return $this;
        }

        /**
         * @return string
         */
        public function getStatus() {
            return $this->status;
        }

        /**
         * @return $this
         */
        public function setPorDerivar() {
            $this->status = 'POR DERIVAR';
            return $this;
        }

        /**
         * @return $this
         */
        public function setPorRecibir() {
            $this->status = 'POR RECIBIR';
            return $this;
        }

        /**
         * @return $this
         */
        public function setEnProceso() {
            $this->status = 'EN PROCESO';
            return $this;
        }

        /**
         * @return $this
         */
        public function setFinalizado() {
            $this->status = 'FINALIZADO';
            return $this;
        }

        /**
         * @return $this
         */
        public function setArchivado() {
            $this->status = 'ARCHIVADO';
            return $this;
        }

        /**
         * $status = [
         * 'POR DERIVAR',
         * 'POR RECIBIR',
         * 'EN PROCESO',
         * 'FINALIZADO',
         * 'ARCHIVADO'
         * ]
         *
         * @param string $status
         *
         * @return DocumentaryFileOffice
         */
        public function setStatus($status)
        : DocumentaryFileOffice {
            $this->status = $status;
            return $this;
        }

        /**
         * @return array
         */
        public function getCollectionData() {
            $class = 'badge bg-secondary text-white ';
            $data = [
                'id' => $this->id ,
                'class' => '',
                'documentary_file_id' => $this->documentary_file_id ,
                'documentary_office_id' => $this->documentary_office_id ,
                'observation' => $this->getObservation() ,
                'office_name' => $this->getOfficeName() ,
                'process_name' => $this-> getProcessName(),
                'documentary_process_id' => $this-> documentary_process_id,
                'complete' => $this->getComplete(),
                'start_date' => $this->getStartDate(),
                'end_date' => $this->getEndDate() ,
                'days' => $this->getDays(),
                'created_at' => $this->created_at->format('Y-m-d H:m'),
            ];
            if(!empty( $this->getEndDate() )){
                $now = Carbon::now();
                $parse = Carbon::createFromFormat('Y-m-d H:i:s',$this->getEndDate());
                if($now > $parse){
                    $data['class'] = $class."bg-danger";
                }else{
                    $data['class'] = $class."bg-success";
                }
            }
            $data['documentary_office'] = DocumentaryOffice::find($this->documentary_office_id);
            $data['documentary_file'] = DocumentaryFile::find($this->documentary_file_id);


            // $data['documentary_action'] = DocumentaryAction::find($this->documentary_action_id);
            return $data;
        }

        /**
         * @return string|null
         */
        public function getOfficeName()
        : ?string {
            return $this->office_name;
        }

        /**
         * @param string|null $office_name
         *
         * @return DocumentaryFileOffice
         */
        public function setOfficeName(?string $office_name)
        : DocumentaryFileOffice {
            $this->office_name = $office_name;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getProcessName()
        : ?string {
            return $this->process_name;
        }

        /**
         * @param string|null $process_name
         *
         * @return DocumentaryFileOffice
         */
        public function setProcessName(?string $process_name)
        : DocumentaryFileOffice {
            $this->process_name = $process_name;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getDocumentaryProcessId()
        : ?int {
            return $this->documentary_process_id;
        }

        /**
         * @param int|null $documentary_process_id
         *
         * @return DocumentaryFileOffice
         */
        public function setDocumentaryProcessId(?int $documentary_process_id)
        : DocumentaryFileOffice {
            $this->documentary_process_id = $documentary_process_id;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function getComplete()
        : ?bool {
            return $this->complete;
        }

        /**
         * @param bool|null $complete
         *
         * @return DocumentaryFileOffice
         */
        public function setComplete(?bool $complete)
        : DocumentaryFileOffice {
            $this->complete = $complete;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getStartDate()
         {
            return $this->start_date;
        }

        /**
         * @param \Carbon\Carbon|null $start_date
         *
         * @return DocumentaryFileOffice
         */
        public function setStartDate(?Carbon $start_date)
        : DocumentaryFileOffice {
            $this->start_date = $start_date;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getEndDate(){
            return $this->end_date;
        }

        /**
         * @param \Carbon\Carbon|null $end_date
         *
         * @return DocumentaryFileOffice
         */
        public function setEndDate(?Carbon $end_date)
        : DocumentaryFileOffice {
            $this->end_date = $end_date;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getDays()
        : ?int {
            return $this->days;
        }

        /**
         * @param int|null $days
         *
         * @return DocumentaryFileOffice
         */
        public function setDays(?int $days)
        : DocumentaryFileOffice {
            $this->days = $days;
            return $this;
        }


    }




