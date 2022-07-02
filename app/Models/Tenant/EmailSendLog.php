<?php

    namespace App\Models\Tenant;


    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
     * Class EmailSendLog
     *
     * @property int         $id
     * @property int|null    $relation_id
     * @property string|null $relation_model
     * @property int|null    $sendit
     * @property string|null $email
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @package App\Models
     * @mixin ModelTenant
     */
    class EmailSendLog extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'email_send_log';
        protected $perPage = 25;

        protected $casts = [
            'type' => 'int',
            'relation_id' => 'int',
            'sendit' => 'bool'
        ];

        protected $fillable = [
            'relation_id',
            'relation_model',
            'type',
            'sendit',
            'file_line',
            'email',
        ];

        /**
         * @return int|null
         */
        public function getRelationId(): ?int
        {
            return $this->relation_id;
        }

        /**
         * @param int|null $relation_id
         *
         * @return EmailSendLog
         */
        public function setRelationId(?int $relation_id): EmailSendLog
        {
            $this->relation_id = $relation_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getRelationModel(): ?string
        {
            return $this->relation_model;
        }

        /**
         * @param string|null $model
         *
         * @return EmailSendLog
         */
        public function setRelationModel(?string $relation_model): EmailSendLog
        {
            $this->relation_model = $relation_model;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getSendit(): ?int
        {
            return $this->sendit;
        }

        /**
         * @param int|null $sendit
         *
         * @return EmailSendLog
         */
        public function setSendit(?int $sendit): EmailSendLog
        {
            $this->sendit = $sendit;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getEmail(): ?string
        {
            return $this->email;
        }

        /**
         * @param string|null $email
         *
         * @return EmailSendLog
         */
        public function setEmail(?string $email): EmailSendLog
        {
            $this->email = $email;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getType(): ?int
        {
            return (int)$this->type;
        }

        /**
         * @param int|null $type
         *
         * @return EmailSendLog
         */
        public function setType(?int $type): EmailSendLog
        {
            $this->type = (int) $type;
            return $this;
        }

        /**
         * Type == 1 para Document
         * Type == 2 para SaleNote
         * Type == 3 para Quotation
         * Type == 4 para Dispatch
         * Type == 5 para Purchase
         *
         * @param int $type
         *
         * @return EmailSendLog
         */
        public function setModelByType($type = 0 ){

            if($type == 1){
                $this->relation_model = Document::class;
            }elseif($type == 2){
                $this->relation_model = SaleNote::class;
            }elseif($type == 3){
                $this->relation_model = Quotation::class;
            }elseif($type == 4){
                $this->relation_model = Dispatch::class;
            }elseif($type == 5){
                $this->relation_model = Purchase::class;
            }
            return $this;
        }
        public function scopeDocument($query){ return $query->where('relation_model',Document::class); }
        public function scopeSaleNote($query){ return $query->where('relation_model',SaleNote::class); }
        public function scopeQuotation($query){ return $query->where('relation_model',Quotation::class); }
        public function scopeDispatch($query){ return $query->where('relation_model',Dispatch::class); }
        public function scopePurchase($query){ return $query->where('relation_model',Purchase::class); }
        public function scopeFindRelationId ($query,$id=0){ return $query->where('relation_id',$id);}

        /**
         * @return string|null
         */
        public function getFileLine(): ?string
        {
            return $this->file_line;
        }

        /**
         * @param string|null $file_line
         *
         * @return EmailSendLog
         */
        public function setFileLine(?string $file_line): EmailSendLog
        {
            $this->file_line = $file_line;
            return $this;
        }

    }
