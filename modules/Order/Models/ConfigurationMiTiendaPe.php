<?php

    namespace Modules\Order\Models;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
     * Class ConfigurationMiTiendaPe
     *
     * @property int         $id
     * @property int|null    $establishment_id
     * @property int|null    $series_order_note_id
     * @property int|null    $series_document_ft_id
     * @property int|null    $series_document_bt_id
     * @property bool|null    $autogenerate
     * @property int|null    $user_id
     * @property int|null    $payment_destination_id
     * @property string|null $currency_type_id
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @mixin ModelTenant
     * @mixin Eloquent
     * @package Modules\Order\Models
     */
    class ConfigurationMiTiendaPe extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'configuration_mi_tienda_pe';

        protected $casts = [
            'establishment_id' => 'int',
            'series_order_note_id' => 'int',
            'series_document_ft_id' => 'int',
            'series_document_bt_id' => 'int',
            'user_id' => 'int',
            'payment_destination_id' => 'int',
            'autogenerate' => 'bool',
        ];

        protected $fillable = [
            'establishment_id',
            'series_order_note_id',
            'series_document_ft_id',
            'series_document_bt_id',
            'user_id',
            'autogenerate',
            'payment_destination_id',
            'currency_type_id'
        ];

        /**
         * @return bool
         */
        public function getAutogenerate(): bool
        {
            return (bool)$this->autogenerate;
        }

        /**
         * @param bool|null $autogenerate
         *
         * @return ConfigurationMiTiendaPe
         */
        public function setAutogenerate(?bool $autogenerate): ConfigurationMiTiendaPe
        {
            $this->autogenerate = (bool)$autogenerate;
            return $this;
        }

    }

