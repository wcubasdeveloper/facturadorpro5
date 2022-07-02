<?php


    namespace App\Models\Tenant;


    use Auth;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Modules\Finance\Models\GlobalPayment;

    /**
     * Class TransferAccountPayment
     *
     * @property int         $id
     * @property int|null    $origin_id
     * @property string|null $origin_type
     * @property int|null    $destiny_id
     * @property string|null $destiny_type
     * @property float|null  $amount
     * @property Carbon|null $date_of_movement
     * @property int|null    $user_id
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @package App\Models
     */
    class TransferAccountPayment extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;
        protected $casts = [
            'origin_id' => 'int',
            'destiny_id' => 'int',
            'amount' => 'float',
            'user_id' => 'int'
        ];
        protected $dates = [
            'date_of_movement'
        ];
        protected $fillable = [
            'origin_id',
            'origin_type',
            'destiny_id',
            'destiny_type',
            'amount',
            'date_of_movement',
            'user_id',
        ];

        protected static function boot()
        {
            parent::boot();

            static::saved(function ($model) {
                /** @var TransferAccountPayment $model */
                $company = Company::active();

                $data = [
                    'soap_type_id' => $company->soap_type_id,
                    'destination_id' => $model->destiny_id,
                    'destination_type' => $model->destiny_type,
                    'user_id' => $model->user_id,
                    'payment_id' => $model->id,
                    'payment_type' => TransferAccountPayment::class,
                ];
                $e = GlobalPayment::where($data)->first();
                if (empty($e)) {
                    $e = new GlobalPayment($data);
                    $e->fill($data)->push();
                }
            });
        }

        /**
         * @return int|null
         */
        public function getOriginId(): ?int
        {
            return $this->origin_id;
        }

        /**
         * @param int|null $origin_id
         *
         * @return TransferAccountPayment
         */
        public function setOriginId(?int $origin_id): TransferAccountPayment
        {
            $this->origin_id = $origin_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getOriginType(): ?string
        {
            return $this->origin_type;
        }

        /**
         * @param string|null $origin_type
         *
         * @return TransferAccountPayment
         */
        public function setOriginType(?string $origin_type): TransferAccountPayment
        {
            $this->origin_type = $origin_type;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getDestinyId(): ?int
        {
            return $this->destiny_id;
        }

        /**
         * @param int|null $destiny_id
         *
         * @return TransferAccountPayment
         */
        public function setDestinyId(?int $destiny_id): TransferAccountPayment
        {
            $this->destiny_id = $destiny_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDestinyType(): ?string
        {
            return $this->destiny_type;
        }

        /**
         * @param string|null $destiny_type
         *
         * @return TransferAccountPayment
         */
        public function setDestinyType(?string $destiny_type): TransferAccountPayment
        {
            $this->destiny_type = $destiny_type;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getAmount(): ?float
        {
            return $this->amount;
        }

        /**
         * @param float|null $amount
         *
         * @return TransferAccountPayment
         */
        public function setAmount(?float $amount): TransferAccountPayment
        {
            $this->amount = $amount;
            return $this;
        }

        /**
         * @return Carbon|null
         */
        public function getDateOfMovement(): ?Carbon
        {
            return $this->date_of_movement;
        }

        /**
         * @param Carbon|null $date_of_movement
         *
         * @return TransferAccountPayment
         */
        public function setDateOfMovement(?Carbon $date_of_movement): TransferAccountPayment
        {
            $this->date_of_movement = $date_of_movement;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getUserId(): ?int
        {
            return $this->user_id;
        }

        /**
         * @param int|null $user_id
         *
         * @return TransferAccountPayment
         */
        public function setUserId(?int $user_id): TransferAccountPayment
        {
            $this->user_id = $user_id;
            return $this;
        }

        public function TransforFromCashToBank($cash_id = 0, $bank_id = 0, $amount = 0)
        {

            return $this->FillFromData(
                $cash_id,
                Cash::class,
                $bank_id,
                BankAccount::class,
                $amount

            );
        }

        protected function FillFromData(
            $origin_id = 0,
            $origin_type = '',
            $destiny_id = 0,
            $destiny_type = '',
            $amount = 0
        )
        {
            $data = [
                'origin_id' => (int)$origin_id,
                'origin_type' => $origin_type,
                'destiny_id' => (int)$destiny_id,
                'destiny_type' => $destiny_type,
                'amount' => (float)$amount,
                'date_of_movement' => Carbon::now(),
                'user_id' => Auth::user()->id,
            ];
            $this->fill($data);
            return $this;

        }

        public function TransforFromCashToCash($cash_a_id = 0, $cash_b_id = 0, $amount = 0)
        {

            return $this->FillFromData(
                $cash_a_id,
                Cash::class,
                $cash_b_id,
                Cash::class,
                $amount

            );
        }

        public function TransforFromBankToBank($bank_a_id = 0, $bank_b_id = 0, $amount = 0)
        {

            return $this->FillFromData(
                $bank_a_id,
                BankAccount::class,
                $bank_b_id,
                BankAccount::class,
                $amount

            );
        }

        public function TransforFromBankToCash($bank_id, $cash_id = 0, $amount = 0)
        {

            return $this->FillFromData(
                $bank_id,
                BankAccount::class,
                $cash_id,
                Cash::class,

                $amount
            );
        }
    }
