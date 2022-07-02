<?php

    namespace App\Models\Tenant;


    use App\Models\Tenant\Catalogs\CurrencyType;
    use Auth;
    use Carbon\Carbon;
    use Illuminate\Config\Repository;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Config;
    use Modules\Inventory\Models\Warehouse;
    use Modules\LevelAccess\Models\ModuleLevel;
    use App\Models\Tenant\Skin;

    /**
     * Class App\Models\Tenant\Configuration
     *
     * @property int         $id
     * @property bool        $send_auto
     * @property string      $formats
     * @property bool        $cron
     * @property bool        $mi_tienda_pe
     * @property bool        $stock
     * @property bool        $sunat_alternate_server
     * @property int         $limit_documents
     * @property int         $limit_users
     * @property bool        $locked_emission
     * @property bool        $permission_to_edit_cpe
     * @property bool        $set_address_by_establishment
     * @property string|null $plan
     * @property bool        $enable_whatsapp
     * @property string|null $phone_whatsapp
     * @property string|null $apk_url
     * @property string|null $visual
     * @property int         $decimal_quantity
     * @property bool        $locked_users
     * @property Carbon|null $date_time_start
     * @property int         $quantity_documents
     * @property bool        $locked_tenant
     * @property bool        $compact_sidebar
     * @property float       $amount_plastic_bag_taxes
     * @property bool        $config_system_env
     * @property int|null    $colums_grid_item
     * @property bool        $options_pos
     * @property bool        $edit_name_product
     * @property bool        $restrict_receipt_date
     * @property string      $affectation_igv_type_id
     * @property bool|null   $include_igv
     * @property float|null  $percentage_allowance_charge
     * @property float       $igv_retention_percentage
     * @property bool|null   $active_allowance_charge
     * @property bool|null   $active_warehouse_prices
     * @property bool|null   $product_only_location
     * @property string|null $terms_condition
     * @property string|null $terms_condition_sale
     * @property bool        $cotizaction_finance
     * @property bool        $quotation_allow_seller_generate_sale
     * @property bool        $allow_edit_unit_price_to_seller
     * @property bool        $legend_footer
     * @property string|null $header_image
     * @property bool        $destination_sale
     * @property bool        $default_document_type_03
     * @property bool        $default_document_type_80
     * @property bool        $search_item_by_barcode
     * @property string|null $login
     * @property string      $navbar
     * @property string|null $finances
     * @property string|null $smtp_encryption
     * @property string|null $smtp_password
     * @property string|null $smtp_user
     * @property int         $smtp_port
     * @property string|null $smtp_host
     * @property bool        $ticket_58
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property bool        $seller_can_create_product
     * @property bool        $seller_can_view_balance
     * @property bool        $seller_can_generate_sale_opportunities
     * @property bool        $update_document_on_dispaches
     * @property bool        $is_pharmacy
     * @property int|null    $auto_send_dispatchs_to_sunat
     * @property bool        $send_data_to_other_server
     * @property bool        $search_item_by_series
     * @property bool        $group_items_generate_document
     * @property bool        $change_free_affectation_igv
     * @property bool        $pos_history
     * @property bool        $pos_cost_price
     * @property bool        $show_totals_on_cpe_list
     * @property string|null $currency_type_id
     * @property bool        $select_available_price_list
     * @property int|null    $show_extra_info_to_item
     * @property int|null    $enabled_global_igv_to_purchase
     * @property int|null    $show_pdf_name
     * @property int|null    $dispatches_address_text
     * @property int|null    $show_items_only_user_stablishment
     * @property int|null    $new_validator_pagination
     * @property bool        $name_product_pdf_to_xml
     * @property int         $item_name_pdf_description
     * @property bool        $auto_print
     * @property bool        $print_new_line_to_observation
     * @property bool        $show_service_on_pos
     * @property bool        $locked_admin
     * @property string|null $certificate
     * @property string|null $soap_send_id
     * @property string|null $soap_type_id
     * @property string|null $soap_username
     * @property string|null $soap_password
     * @property string|null $soap_url
     * @property string|null $token_public_culqui
     * @property string|null $token_private_culqui
     * @property string|null $url_apiruc
     * @property string|null $token_apiruc
     * @property bool        $use_login_global
     * @property bool|false  $show_terms_condition_pos
     * @package App\Models\Tenant
     * @mixin ModelTenant
     * @method static Builder|Configuration newModelQuery()
     * @method static Builder|Configuration newQuery()
     * @method static Builder|Configuration query()
     * @method static Builder|Configuration whereCertificate($value)
     * @method static Builder|Configuration whereCreatedAt($value)
     * @method static Builder|Configuration whereId($value)
     * @method static Builder|Configuration whereLockedAdmin($value)
     * @method static Builder|Configuration whereLogin($value)
     * @method static Builder|Configuration whereSoapPassword($value)
     * @method static Builder|Configuration whereSoapSendId($value)
     * @method static Builder|Configuration whereSoapTypeId($value)
     * @method static Builder|Configuration whereSoapUrl($value)
     * @method static Builder|Configuration whereSoapUsername($value)
     * @method static Builder|Configuration whereTokenApiruc($value)
     * @method static Builder|Configuration whereTokenPrivateCulqui($value)
     * @method static Builder|Configuration whereTokenPublicCulqui($value)
     * @method static Builder|Configuration whereUpdatedAt($value)
     * @method static Builder|Configuration whereUrlApiruc($value)
     * @method static Builder|Configuration whereUseLoginGlobal($value)
     * @method static Builder|Configuration whereApkUrl($value)
     */
    class Configuration extends ModelTenant
    {
        protected $fillable = [
            'active_allowance_charge',
            'active_warehouse_prices',
            'affectation_igv_type_id',
            'allow_edit_unit_price_to_seller',
            'amount_plastic_bag_taxes',
            'apk_url',
            'auto_print',
            'auto_send_dispatchs_to_sunat',
            'change_free_affectation_igv',
            'colums_grid_item',
            'compact_sidebar',
            'config_system_env',
            'cotizaction_finance',
            'cron',
            'currency_type_id',
            'date_time_start',
            'decimal_quantity',
            'default_document_type_03',
            'default_document_type_80',
            'destination_sale',
            'dispatches_address_text',
            'edit_name_product',
            'enable_whatsapp',
            'enabled_global_igv_to_purchase',
            'finances',
            'formats',
            'group_items_generate_document',
            'header_image',
            'igv_retention_percentage',
            'include_igv',
            'is_pharmacy',
            'item_name_pdf_description',
            'legend_footer',
            'limit_documents',
            'limit_users',
            'locked_emission',
            'locked_tenant',
            'locked_users',
            'login',
            'name_product_pdf_to_xml',
            'navbar',
            'options_pos',
            'percentage_allowance_charge',
            'permission_to_edit_cpe',
            'phone_whatsapp',
            'plan',
            'product_only_location',
            'quantity_documents',
            'quotation_allow_seller_generate_sale',
            'restrict_receipt_date',
            'search_item_by_barcode',
            'search_item_by_series',
            'select_available_price_list',
            'seller_can_create_product',
            'seller_can_generate_sale_opportunities',
            'seller_can_view_balance',
            'send_auto',
            'send_data_to_other_server',
            'set_address_by_establishment',
            'show_extra_info_to_item',
            'show_items_only_user_stablishment',
            'show_pdf_name',
            'smtp_encryption',
            'smtp_host',
            'smtp_password',
            'smtp_port',
            'smtp_user',
            'stock',
            'sunat_alternate_server',
            'terms_condition_sale',
            'terms_condition',
            'ticket_58',
            'pos_history',
            'pos_cost_price',
            'update_document_on_dispaches',
            'show_service_on_pos',
            'visual',
            'show_totals_on_cpe_list',
            'mi_tienda_pe',
            'detraction_amount_rounded_int',
            'validate_purchase_sale_unit_price',
            'show_terms_condition_pos',
            'show_ticket_80',
            'show_ticket_58',
            'show_ticket_50',
            'show_last_price_sale',
            'print_new_line_to_observation',
            'show_logo_by_establishment',
            'global_discount_type_id',
            'shipping_time_days',
            'url_apiruc',
            'new_validator_pagination',
            'token_apiruc',
            'customer_filter_by_seller',
            'checked_global_igv_to_purchase',
            'checked_update_purchase_price',
            'set_global_purchase_currency_items',
            'set_unit_price_dispatch_related_record',
            'restrict_voided_send',
            'shipping_time_days_voided',
            'top_menu_a_id',
            'top_menu_b_id',
            'top_menu_c_id',
            'top_menu_d_id',
            'skin_id',
            'enabled_tips_pos',
            'legend_forest_to_xml',
            'change_currency_item',
            'enabled_advanced_records_search',
            'change_decimal_quantity_unit_price_pdf',
            'decimal_quantity_unit_price_pdf',
        ];

        protected $casts = [
            'quotation_allow_seller_generate_sale' => 'boolean',
            'allow_edit_unit_price_to_seller' => 'boolean',
            'seller_can_create_product' => 'boolean',
            'seller_can_generate_sale_opportunities' => 'boolean',
            'seller_can_view_balance' => 'boolean',
            'update_document_on_dispaches' => 'boolean',
            'show_service_on_pos' => 'boolean',
            'is_pharmacy' => 'boolean',
            'auto_send_dispatchs_to_sunat' => 'boolean',
            'send_data_to_other_server' => 'boolean',
            'select_available_price_list' => 'boolean',
            'show_extra_info_to_item' => 'boolean',
            'group_items_generate_document' => 'boolean',
            'enabled_global_igv_to_purchase' => 'boolean',
            'show_pdf_name' => 'boolean',
            'mi_tienda_pe' => 'boolean',
            'dispatches_address_text' => 'boolean',
            'set_address_by_establishment' => 'boolean',
            'show_items_only_user_stablishment' => 'boolean',
            'permission_to_edit_cpe' => 'boolean',
            'name_product_pdf_to_xml' => 'boolean',
            'item_name_pdf_description' => 'boolean',
            'default_document_type_80' => 'boolean',
            'search_item_by_barcode' => 'boolean',
            'send_auto' => 'bool',
            'cron' => 'bool',
            'stock' => 'bool',
            'sunat_alternate_server' => 'bool',
            'limit_documents' => 'int',
            'limit_users' => 'int',
            'locked_emission' => 'bool',
            'enable_whatsapp' => 'bool',
            'decimal_quantity' => 'int',
            'locked_users' => 'bool',
            'quantity_documents' => 'int',
            'locked_tenant' => 'bool',
            'compact_sidebar' => 'bool',
            'amount_plastic_bag_taxes' => 'float',
            'config_system_env' => 'bool',
            'colums_grid_item' => 'int',
            'options_pos' => 'bool',
            'edit_name_product' => 'bool',
            'restrict_receipt_date' => 'bool',
            'include_igv' => 'bool',
            'percentage_allowance_charge' => 'float',
            'igv_retention_percentage' => 'float',
            'active_allowance_charge' => 'bool',
            'active_warehouse_prices' => 'bool',
            'product_only_location' => 'bool',
            'cotizaction_finance' => 'bool',
            'legend_footer' => 'bool',
            'destination_sale' => 'bool',
            'default_document_type_03' => 'bool',
            'smtp_port' => 'int',
            'ticket_58' => 'bool',
            'search_item_by_series' => 'bool',
            'change_free_affectation_igv' => 'bool',
            'pos_history' => 'bool',
            'pos_cost_price' => 'bool',
            'show_totals_on_cpe_list' => 'bool',
            'auto_print' => 'bool',
            'detraction_amount_rounded_int' => 'bool',
            'validate_purchase_sale_unit_price' => 'bool',
            'show_terms_condition_pos' => 'bool',
            'show_last_price_sale' => 'bool',
            'show_logo_by_establishment' => 'bool',
            'print_new_line_to_observation' => 'bool',
            'shipping_time_days' => 'int',
            'new_validator_pagination' => 'int',
            'customer_filter_by_seller' => 'bool',
            'checked_global_igv_to_purchase' => 'bool',
            'checked_update_purchase_price' => 'bool',
            'set_global_purchase_currency_items' => 'bool',
            'set_unit_price_dispatch_related_record' => 'bool',
            'restrict_voided_send' => 'bool',
            'shipping_time_days_voided' => 'int',
            'top_menu_a_id' => 'int',
            'top_menu_b_id' => 'int',
            'top_menu_c_id' => 'int',
            'top_menu_d_id' => 'int',
            'skin_id' => 'int',
            'enabled_tips_pos' => 'bool',
            'legend_forest_to_xml' => 'bool',
            'change_currency_item' => 'bool',
            'enabled_advanced_records_search' => 'bool',
            'change_decimal_quantity_unit_price_pdf' => 'bool',
            'decimal_quantity_unit_price_pdf' => 'int',

        ];

        protected $hidden = [
            'smtp_password'
        ];

        public static function boot()
        {
            parent::boot();
            static::creating(function (self $item) {

                //i f(empty($item->apk_url)) $item->apk_url = 'https://facturaloperu.com/apk/app-debug.apk';
            });
            static::retrieved(function (self $item) {

                // if (empty($item->apk_url)) $item->apk_url = 'https://facturaloperu.com/apk/app-debug.apk';
            });

        }

        /**
         * Establece las configuraciones para envio de correo.
         *
         * @return Configuration
         * @example
         *         <code>
         *  <?php
         *  Configuration::setConfigSmtpMail();
         *  ?>
         *         </code>
         */
        public static function setConfigSmtpMail()
        {
            $config = self::first();
            if (empty($config)) $config = new self();
            if (
                !empty($config->smtp_host) &&
                !empty($config->smtp_port) &&
                !empty($config->smtp_user) &&
                !empty($config->smtp_password) &&
                !empty($config->smtp_encryption)
            ) {
                Config::set('mail.host', $config->smtp_host);
                Config::set('mail.port', $config->smtp_port);
                Config::set('mail.username', $config->smtp_user);
                Config::set('mail.password', $config->smtp_password);
                Config::set('mail.encryption', $config->smtp_encryption);
            }
            return $config;
        }

        /**
         * Devuelve un json con las propiedades excluidas
         *
         * @return string
         */
        public static function getPublicConfig()
        {
            $conf = self::first();
            $data = $conf->getCollectionData();

            return json_encode($data);

        }

        /**
         * @return array
         */
        public function getCollectionData()
        {
            $company = Company::first();
            /** @var User $user */
            $user = new User();
            $productionApp = false;
            if (Auth::user()) {
                $user = auth()->user();
                $productionApp = !empty($user->modules->where('value', 'production_app')->first());
                // se busca el permiso para produccion app

            }

            $establishment = $user->establishment;
            $establishment_id = $user->establishment_id;
            $serie = $user->series_id;
            $document_id = $user->document_id;
            $typeUser = $user->type;
            $unit_type_id = 'KGM'; //Unidad de medida por defecto
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            if ($warehouse == null) {
                $warehouse = new Warehouse();
            }
            $currency = CurrencyType::all();
            $skins = Skin::all();
            return [
                'id' => $this->id,
                'company' => $company,
                'establishment' => $establishment,
                'production_app' => $productionApp,
                'warehouse_id' => $warehouse->id,
                'send_auto' => (bool)$this->send_auto,
                'formats' => $this->formats,
                'stock' => (bool)$this->stock,
                'cron' => (bool)$this->cron,
                'sunat_alternate_server' => (bool)$this->sunat_alternate_server,
                'compact_sidebar' => (bool)$this->compact_sidebar,
                'subtotal_account' => $this->subtotal_account,
                'decimal_quantity' => $this->decimal_quantity,
                'amount_plastic_bag_taxes' => $this->amount_plastic_bag_taxes,
                'colums_grid_item' => $this->colums_grid_item,
                'options_pos' => (bool)$this->options_pos,
                'edit_name_product' => (bool)$this->edit_name_product,
                'restrict_receipt_date' => (bool)$this->restrict_receipt_date,
                'affectation_igv_type_id' => $this->affectation_igv_type_id,
                'visual' => $this->visual,
                'enable_whatsapp' => (bool)$this->enable_whatsapp,
                'phone_whatsapp' => $this->enable_whatsapp,
                'apk_url' => $this->apk_url,
                'terms_condition' => $this->terms_condition,
                'terms_condition_sale' => $this->terms_condition_sale,
                'cotizaction_finance' => (bool)$this->cotizaction_finance,
                'include_igv' => (bool)$this->include_igv,
                'product_only_location' => (bool)$this->product_only_location,
                'legend_footer' => (bool)$this->legend_footer,
                'default_document_type_03' => (bool)$this->default_document_type_03,
                'header_image' => $this->header_image,
                'destination_sale' => (bool)$this->destination_sale,
                'quotation_allow_seller_generate_sale' => $this->quotation_allow_seller_generate_sale,
                'allow_edit_unit_price_to_seller' => $this->allow_edit_unit_price_to_seller,
                'finances' => $this->finances,
                'ticket_58' => (bool)$this->ticket_58,
                'seller_can_create_product' => (bool)$this->seller_can_create_product,
                'seller_can_view_balance' => (bool)$this->seller_can_view_balance,
                'seller_can_generate_sale_opportunities' => (bool)$this->seller_can_generate_sale_opportunities,
                'update_document_on_dispaches' => (bool)$this->update_document_on_dispaches,
                'is_pharmacy' => (bool)$this->is_pharmacy,
                'auto_send_dispatchs_to_sunat' => (bool)$this->auto_send_dispatchs_to_sunat,
                'send_data_to_other_server' => (bool)$this->send_data_to_other_server,
                'item_per_page' => config('tenant.items_per_page'),
                'active_warehouse_prices' => (bool)$this->active_warehouse_prices,
                'active_allowance_charge' => (bool)$this->active_allowance_charge,
                'dispatches_address_text' => $this->isDispatchesAddressText(),
                'show_items_only_user_stablishment' => $this->isShowItemsOnlyUserStablishment(),
                'search_item_by_series' => (bool)$this->search_item_by_series,
                'change_free_affectation_igv' => (bool)$this->change_free_affectation_igv,
                'select_available_price_list' => (bool)$this->select_available_price_list,
                'show_extra_info_to_item' => $this->isShowExtraInfoToItem(),
                'percentage_allowance_charge' => $this->percentage_allowance_charge,
                'group_items_generate_document' => $this->group_items_generate_document,
                'set_address_by_establishment' => $this->set_address_by_establishment,
                'permission_to_edit_cpe' => $this->permission_to_edit_cpe,
                'name_product_pdf_to_xml' => $this->name_product_pdf_to_xml,
                'default_document_type_80' => $this->default_document_type_80,
                'search_item_by_barcode' => $this->search_item_by_barcode,
                'igv_retention_percentage' => $this->igv_retention_percentage,
                'currency_type_id' => $this->getCurrencyTypeId(),
                'currency_types' => $currency,
                'affectation_igv_types_exonerated_unaffected' => Item::AffectationIgvTypesExoneratedUnaffected(),
                'typeUser' => $typeUser,
                'unit_type_id' => $unit_type_id,
                'enabled_global_igv_to_purchase' => $this->isEnabledGlobalIgvToPurchase(),
                'show_pdf_name' => $this->isShowPdfName(),
                'item_name_pdf_description' => $this->isItemNamePdfDescription(),
                'api_service_token' => self::getApiServiceToken(),
                'user' => [
                    'serie' => $serie,
                    'document_id' => $document_id,
                    'type' => $typeUser,
                ],
                'auto_print' => (bool)$this->auto_print,
                'show_service_on_pos' => (bool)$this->show_service_on_pos,
                'pos_history' => $this->isPosHistory(),
                'pos_cost_price' => $this->isPosCostPrice(),
                'show_totals_on_cpe_list' => $this->isShowTotalsOnCpeList(),
                'detraction_amount_rounded_int' => $this->detraction_amount_rounded_int,
                'customer_filter_by_seller' => $this->customer_filter_by_seller,
                'validate_purchase_sale_unit_price' => $this->validate_purchase_sale_unit_price,
                'global_discount_type_id' => $this->global_discount_type_id,
                'show_terms_condition_pos' => (bool)$this->show_terms_condition_pos,
                'mi_tienda_pe' => $this->isMiTiendaPe(),
                'show_ticket_80' => (bool)$this->show_ticket_80,
                'show_ticket_58' => (bool)$this->show_ticket_58,
                'show_ticket_50' => (bool)$this->show_ticket_50,
                'show_last_price_sale' => (bool)$this->show_last_price_sale,
                'show_logo_by_establishment' => (bool)$this->show_logo_by_establishment,
                'shipping_time_days' => $this->shipping_time_days,
                'checked_global_igv_to_purchase' => $this->checked_global_igv_to_purchase,
                'checked_update_purchase_price' => $this->checked_update_purchase_price,
                'set_global_purchase_currency_items' => $this->set_global_purchase_currency_items,
                'set_unit_price_dispatch_related_record' => $this->set_unit_price_dispatch_related_record,
                'new_validator_pagination' => $this->getNewValidatorPagination(),
                'restrict_voided_send' => $this->restrict_voided_send,
                'shipping_time_days_voided' => $this->shipping_time_days_voided,
                'top_menu_a' => $this->top_menu_a_id ? $this->top_menu_a : '',
                'top_menu_b' => $this->top_menu_b_id ? $this->top_menu_b : '',
                'top_menu_c' => $this->top_menu_c_id ? $this->top_menu_c : '',
                'top_menu_d' => $this->top_menu_d_id ? $this->top_menu_d : '',
                'skin_id' => $this->skin_id,
                'skins' => $skins,
                'facturalo_server' => true, // $this->getFacturaloConfig(),
                'enabled_tips_pos' => $this->enabled_tips_pos,
                'legend_forest_to_xml' => $this->legend_forest_to_xml,
                'change_currency_item' => $this->change_currency_item,
                'enabled_advanced_records_search' => $this->enabled_advanced_records_search,
                'change_decimal_quantity_unit_price_pdf' => $this->change_decimal_quantity_unit_price_pdf,
                'decimal_quantity_unit_price_pdf' => $this->decimal_quantity_unit_price_pdf,

            ];
        }

        /**
         * @return bool
         */
        public function isDispatchesAddressText(): ?bool
        {
            return (bool)$this->dispatches_address_text;
        }

        /**
         * @return bool
         */
        public function isShowItemsOnlyUserStablishment(): ?bool
        {
            return false;
            return (bool)$this->show_items_only_user_stablishment;
        }

        /**
         * @return string|null
         */
        public function getCurrencyTypeId(): ?string
        {
            return empty($this->currency_type_id) ? 'PEN' : $this->currency_type_id;
        }

        /**
         * @return bool
         */
        public function isEnabledGlobalIgvToPurchase(): ?bool
        {
            return (bool)$this->enabled_global_igv_to_purchase;
        }

        /**
         * @return bool
         */
        public function isShowPdfName(): ?bool
        {
            return (bool)$this->show_pdf_name;
        }

        /**
         * @return bool
         */
        public function isItemNamePdfDescription(): ?bool
        {
            return (bool)$this->item_name_pdf_description;
        }

        /**
         * Devuele el token de apiperu desde configuracion del sistema
         *
         * @return Repository|Application|mixed
         */
        public static function getApiServiceToken()
        {
            $api_service_token = \App\Models\System\Configuration::getApiServiceToken();
            // $api_service_token = $configuration->token_apiruc =! '' ? $configuration->token_apiruc : config('configuration.api_service_token');
            // $api_service_token = $configuration->token_apiruc === 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;

            return $api_service_token;
        }

        /**
         * @return bool
         */
        public function isAutoSendDispatchsToSunat(): bool
        {
            return (bool)$this->auto_send_dispatchs_to_sunat;
        }

        /**
         * @param bool|null $auto_send_dispatchs_to_sunat
         *
         * @return Configuration
         */
        public function setAutoSendDispatchsToSunat(?bool $auto_send_dispatchs_to_sunat): Configuration
        {
            $this->auto_send_dispatchs_to_sunat = (bool)$auto_send_dispatchs_to_sunat;
            return $this;
        }

        /**
         * @return bool
         */
        public function isPharmacy(): bool
        {
            if (empty($this->is_pharmacy)) $this->is_pharmacy = false;
            return (bool)$this->is_pharmacy;
        }

        /**
         * @param bool|null $is_pharmacy
         *
         * @return Configuration
         */
        public function setIsPharmacy(?bool $is_pharmacy): Configuration
        {
            $this->is_pharmacy = (bool)$is_pharmacy;
            return $this;
        }

        /**
         * @return bool
         */
        public function getUpdateDocumentOnDispaches()
        {
            return $this->update_document_on_dispaches;
        }

        /**
         * @param bool $update_document_on_dispaches
         *
         * @return Configuration
         */
        public function setUpdateDocumentOnDispaches($update_document_on_dispaches)
        {
            $this->update_document_on_dispaches = (boolean)$update_document_on_dispaches;
            return $this;
        }

        /**
         * @return bool
         */
        public function getSellerCanGenerateSaleOpportunities()
        {
            return (bool)$this->seller_can_generate_sale_opportunities;
        }

        /**
         * @param bool|null $seller_can_generate_sale_opportunities
         *
         * @return Configuration
         */
        public function setSellerCanGenerateSaleOpportunities($seller_can_generate_sale_opportunities)
        {
            $this->seller_can_generate_sale_opportunities = (bool)$seller_can_generate_sale_opportunities;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getSmtpHost()
        {
            return empty($this->smtp_host) ? config('mail.host') : $this->smtp_host;
        }

        /**
         * @param mixed $smtp_host
         *
         * @return Configuration
         */
        public function setSmtpHost($smtp_host)
        {
            $this->smtp_host = $smtp_host;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getSmtpPort()
        {
            return empty($this->smtp_port) ? config('mail.port') : $this->smtp_port;
        }

        /**
         * @param mixed $smtp_port
         *
         * @return Configuration
         */
        public function setSmtpPort($smtp_port)
        {
            $this->smtp_port = $smtp_port;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getSmtpUser()
        {
            return empty($this->smtp_user) ? config('mail.username') : $this->smtp_user;
        }

        /**
         * @param mixed $smtp_user
         *
         * @return Configuration
         */
        public function setSmtpUser($smtp_user)
        {
            $this->smtp_user = $smtp_user;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getSmtpPassword()
        {
            return empty($this->smtp_password) ? config('mail.password') : $this->smtp_password;
        }

        /**
         * @param mixed $smtp_password
         *
         * @return Configuration
         */
        public function setSmtpPassword($smtp_password)
        {
            $this->smtp_password = $smtp_password;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getSmtpEncryption()
        {
            return empty($this->smtp_encryption) ? config('mail.encryption') : $this->smtp_encryption;
        }

        /**
         * @param mixed $smtp_encryption
         *
         * @return Configuration
         */
        public function setSmtpEncryption($smtp_encryption)
        {
            $this->smtp_encryption = $smtp_encryption;
            return $this;
        }

        public function setPlanAttribute($value)
        {
            $this->attributes['plan'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getPlanAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setVisualAttribute($value)
        {
            $this->attributes['visual'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getVisualAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setLoginAttribute($value)
        {
            $this->attributes['login'] = is_null($value) ? null : json_encode($value);
        }

        public function getLoginAttribute($value)
        {
            return is_null($value) ? null : (object)json_decode($value);
        }

        public function setFinancesAttribute($value)
        {
            $this->attributes['finances'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getFinancesAttribute($value)
        {
            return is_null($value) ? ['apply_arrears' => false, 'arrears_amount' => 0] : (object)json_decode($value);
        }

        /**
         * Devuelve verdadero o falso si esta habilitado el envio de datos a otro servidor
         *
         * @return bool
         */
        public function isSendDataToOtherServer(): ?bool
        {
            return (bool)$this->send_data_to_other_server;
        }

        /**
         * Establece el valor para el envio de datos a otro servidor
         *
         * @param bool|null $send_data_to_other_server
         *
         * @return Configuration
         */
        public function setSendDataToOtherServer(?bool $send_data_to_other_server = false): Configuration
        {
            $this->send_data_to_other_server = (bool)$send_data_to_other_server;
            return $this;
        }

        /**
         * @param string|null $currency_type_id
         */
        public function setCurrencyTypeId(?string $currency_type_id = 'PEN'): Configuration
        {
            $this->currency_type_id = empty($currency_type_id) ? 'PEN' : $currency_type_id;
            return $this;
        }

        /**
         * @return bool
         */
        public function isShowExtraInfoToItem(): ?bool
        {
            return (bool)$this->show_extra_info_to_item;
        }

        /**
         * @param bool|null $show_extra_info_to_item
         *
         * @return Configuration
         */
        public function setShowExtraInfoToItem(?bool $show_extra_info_to_item = false): Configuration
        {
            $this->show_extra_info_to_item = (bool)$show_extra_info_to_item;
            return $this;
        }

        /**
         * @return bool
         */
        public function isSearchItemBySeries(): ?bool
        {
            return (bool)$this->search_item_by_series;
        }

        /**
         * @param bool|null $search_item_by_series
         *
         * @return Configuration
         */
        public function setSearchItemBySeries(?bool $search_item_by_series): Configuration
        {
            $this->search_item_by_series = (bool)$search_item_by_series;
            return $this;
        }

        /**
         * @param bool|null $enabled_global_igv_to_purchase
         *
         * @return Configuration
         */
        public function setEnabledGlobalIgvToPurchase(?bool $enabled_global_igv_to_purchase = false): Configuration
        {
            $this->enabled_global_igv_to_purchase = (bool)$enabled_global_igv_to_purchase;
            return $this;
        }

        /**
         * @param bool|null $show_pdf_name
         *
         * @return Configuration
         */
        public function setShowPdfName(?bool $show_pdf_name): Configuration
        {
            $this->show_pdf_name = (bool)$show_pdf_name;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getApkUrl(): ?string
        {
            return $this->apk_url;
        }

        /**
         * @param string|null $apk_url
         *
         * @return Configuration
         */
        public function setApkUrl(?string $apk_url): Configuration
        {
            $this->apk_url = $apk_url;
            return $this;
        }

        /**
         * @param bool|null $dispatches_address_text
         *
         * @return Configuration
         */
        public function setDispatchesAddressText(?bool $dispatches_address_text): Configuration
        {
            $this->dispatches_address_text = (bool)$dispatches_address_text;
            return $this;
        }

        /**
         * @param bool|null $show_items_only_user_stablishment
         *
         * @return Company
         */
        public function setShowItemsOnlyUserStablishment(?bool $show_items_only_user_stablishment): Configuration
        {
            // $this->show_items_only_user_stablishment = (bool)$show_items_only_user_stablishment;
            $this->show_items_only_user_stablishment = false;
            return $this;
        }

        /**
         * Devuelve el template actual para impresion, si no se encuentra el usuario o establecimiento devolvera el
         * formato que esta en configuracion
         *
         * @return string
         */
        public function getFormatsToTemplates()
        {
            $user = Auth::user();
            if (!empty($user)) {
                $establishment = $user->establishment;
                if (!empty($establishment)) {
                    return $establishment->template_pdf;
                }
            }
            return $this->formats;
        }

        /**
         * @param bool|null $item_name_pdf_description
         *
         * @return Configuration
         */
        public function setItemNamePdfDescription(?bool $item_name_pdf_description): Configuration
        {
            $this->item_name_pdf_description = (bool)$item_name_pdf_description;
            return $this;
        }

        /**
         * @return bool
         */
        public function isSendAuto(): ?bool
        {
            return (bool)$this->send_auto;
        }

        /**
         * @param bool|null $send_auto
         *
         * @return Configuration
         */
        public function setSendAuto(?bool $send_auto): Configuration
        {
            $this->send_auto = (bool)$send_auto;
            return $this;
        }

        /**
         * @return string
         */
        public function getFormats(): string
        {
            return $this->formats;
        }

        /**
         * @param string $formats
         *
         * @return Configuration
         */
        public function setFormats(string $formats): Configuration
        {
            $this->formats = $formats;
            return $this;
        }

        /**
         * @return bool
         */
        public function isCron(): ?bool
        {
            return (bool)$this->cron;
        }

        /**
         * @param bool|null $cron
         *
         * @return Configuration
         */
        public function setCron(?bool $cron): Configuration
        {
            $this->cron = (bool)$cron;
            return $this;
        }

        /**
         * @return bool
         */
        public function isStock(): ?bool
        {
            return (bool)$this->stock;
        }

        /**
         * @param bool|null $stock
         *
         * @return Configuration
         */
        public function setStock(?bool $stock): Configuration
        {
            $this->stock = (bool)$stock;
            return $this;
        }

        /**
         * @return bool
         */
        public function isSunatAlternateServer(): ?bool
        {
            return (bool)$this->sunat_alternate_server;
        }

        /**
         * @param bool|null $sunat_alternate_server
         *
         * @return Configuration
         */
        public function setSunatAlternateServer(?bool $sunat_alternate_server): Configuration
        {
            $this->sunat_alternate_server = (bool)$sunat_alternate_server;
            return $this;
        }

        /**
         * @return int
         */
        public function getLimitDocuments(): int
        {
            return $this->limit_documents;
        }

        /**
         * @param int $limit_documents
         *
         * @return Configuration
         */
        public function setLimitDocuments(int $limit_documents): Configuration
        {
            $this->limit_documents = $limit_documents;
            return $this;
        }

        /**
         * @return int
         */
        public function getLimitUsers(): int
        {
            return $this->limit_users;
        }

        /**
         * @param int $limit_users
         *
         * @return Configuration
         */
        public function setLimitUsers(int $limit_users): Configuration
        {
            $this->limit_users = $limit_users;
            return $this;
        }

        /**
         * @return bool
         */
        public function isLockedEmission(): ?bool
        {
            return (bool)$this->locked_emission;
        }

        /**
         * @param bool|null $locked_emission
         *
         * @return Configuration
         */
        public function setLockedEmission(?bool $locked_emission): Configuration
        {
            $this->locked_emission = (bool)$locked_emission;
            return $this;
        }

        /**
         * @return bool
         */
        public function isPermissionToEditCpe(): ?bool
        {
            return (bool)$this->permission_to_edit_cpe;
        }

        /**
         * @param bool|null $permission_to_edit_cpe
         *
         * @return Configuration
         */
        public function setPermissionToEditCpe(?bool $permission_to_edit_cpe): Configuration
        {
            $this->permission_to_edit_cpe = (bool)$permission_to_edit_cpe;
            return $this;
        }

        /**
         * @return bool
         */
        public function isSetAddressByEstablishment(): ?bool
        {
            return (bool)$this->set_address_by_establishment;
        }

        /**
         * @param bool|null $set_address_by_establishment
         *
         * @return Configuration
         */
        public function setSetAddressByEstablishment(?bool $set_address_by_establishment): Configuration
        {
            $this->set_address_by_establishment = $set_address_by_establishment;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPlan(): ?string
        {
            return $this->plan;
        }

        /**
         * @param string|null $plan
         *
         * @return Configuration
         */
        public function setPlan(?string $plan): Configuration
        {
            $this->plan = $plan;
            return $this;
        }

        /**
         * @return bool
         */
        public function isEnableWhatsapp(): ?bool
        {
            return (bool)$this->enable_whatsapp;
        }

        /**
         * @param bool|null $enable_whatsapp
         *
         * @return Configuration
         */
        public function setEnableWhatsapp(?bool $enable_whatsapp): Configuration
        {
            $this->enable_whatsapp = (bool)$enable_whatsapp;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPhoneWhatsapp(): ?string
        {
            return $this->phone_whatsapp;
        }

        /**
         * @param string|null $phone_whatsapp
         *
         * @return Configuration
         */
        public function setPhoneWhatsapp(?string $phone_whatsapp): Configuration
        {
            $this->phone_whatsapp = $phone_whatsapp;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getVisual(): ?string
        {
            return $this->visual;
        }

        /**
         * @param string|null $visual
         *
         * @return Configuration
         */
        public function setVisual(?string $visual): Configuration
        {
            $this->visual = $visual;
            return $this;
        }

        /**
         * @return int
         */
        public function getDecimalQuantity(): int
        {
            return $this->decimal_quantity;
        }

        /**
         * @param int $decimal_quantity
         *
         * @return Configuration
         */
        public function setDecimalQuantity(int $decimal_quantity): Configuration
        {
            $this->decimal_quantity = $decimal_quantity;
            return $this;
        }

        /**
         * @return bool
         */
        public function isLockedUsers(): ?bool
        {
            return (bool)$this->locked_users;
        }

        /**
         * @param bool|null $locked_users
         *
         * @return Configuration
         */
        public function setLockedUsers(?bool $locked_users): Configuration
        {
            $this->locked_users = (bool)$locked_users;
            return $this;
        }

        /**
         * @return Carbon|null
         */
        public function getDateTimeStart(): ?Carbon
        {
            return $this->date_time_start;
        }

        /**
         * @param Carbon|null $date_time_start
         *
         * @return Configuration
         */
        public function setDateTimeStart(?Carbon $date_time_start): Configuration
        {
            $this->date_time_start = $date_time_start;
            return $this;
        }

        /**
         * @return int
         */
        public function getQuantityDocuments(): int
        {
            return $this->quantity_documents;
        }

        /**
         * @param int $quantity_documents
         *
         * @return Configuration
         */
        public function setQuantityDocuments(int $quantity_documents): Configuration
        {
            $this->quantity_documents = $quantity_documents;
            return $this;
        }

        /**
         * @return bool
         */
        public function isLockedTenant(): ?bool
        {
            return (bool)$this->locked_tenant;
        }

        /**
         * @param bool|null $locked_tenant
         *
         * @return Configuration
         */
        public function setLockedTenant(?bool $locked_tenant): Configuration
        {
            $this->locked_tenant = (bool)$locked_tenant;
            return $this;
        }

        /**
         * @return bool
         */
        public function isCompactSidebar(): ?bool
        {
            return (bool)$this->compact_sidebar;
        }

        /**
         * @param bool|null $compact_sidebar
         *
         * @return Configuration
         */
        public function setCompactSidebar(?bool $compact_sidebar): Configuration
        {
            $this->compact_sidebar = (bool)$compact_sidebar;
            return $this;
        }

        /**
         * @return float
         */
        public function getAmountPlasticBagTaxes(): float
        {
            return (bool)$this->amount_plastic_bag_taxes;
        }

        /**
         * @param float $amount_plastic_bag_taxes
         *
         * @return Configuration
         */
        public function setAmountPlasticBagTaxes(float $amount_plastic_bag_taxes): Configuration
        {
            $this->amount_plastic_bag_taxes = (bool)$amount_plastic_bag_taxes;
            return $this;
        }

        /**
         * @return bool
         */
        public function isConfigSystemEnv(): ?bool
        {
            return (bool)$this->config_system_env;
        }

        /**
         * @param bool|null $config_system_env
         *
         * @return Configuration
         */
        public function setConfigSystemEnv(?bool $config_system_env): Configuration
        {
            $this->config_system_env = (bool)$config_system_env;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getColumsGridItem(): ?int
        {
            return (bool)$this->colums_grid_item;
        }

        /**
         * @param int|null $colums_grid_item
         *
         * @return Configuration
         */
        public function setColumsGridItem(?int $colums_grid_item): Configuration
        {
            $this->colums_grid_item = (bool)$colums_grid_item;
            return $this;
        }

        /**
         * @return bool
         */
        public function isOptionsPos(): ?bool
        {
            return (bool)$this->options_pos;
        }

        /**
         * @param bool|null $options_pos
         *
         * @return Configuration
         */
        public function setOptionsPos(?bool $options_pos): Configuration
        {
            $this->options_pos = (bool)$options_pos;
            return $this;
        }

        /**
         * @return bool
         */
        public function isEditNameProduct(): ?bool
        {
            return (bool)$this->edit_name_product;
        }

        /**
         * @param bool|null $edit_name_product
         *
         * @return Configuration
         */
        public function setEditNameProduct(?bool $edit_name_product): Configuration
        {
            $this->edit_name_product = (bool)$edit_name_product;
            return $this;
        }

        /**
         * @return bool
         */
        public function isRestrictReceiptDate(): ?bool
        {
            return (bool)$this->restrict_receipt_date;
        }

        /**
         * @param bool|null $restrict_receipt_date
         *
         * @return Configuration
         */
        public function setRestrictReceiptDate(?bool $restrict_receipt_date): Configuration
        {
            $this->restrict_receipt_date = (bool)$restrict_receipt_date;
            return $this;
        }

        /**
         * @return string
         */
        public function getAffectationIgvTypeId(): string
        {
            return (bool)$this->affectation_igv_type_id;
        }

        /**
         * @param string $affectation_igv_type_id
         *
         * @return Configuration
         */
        public function setAffectationIgvTypeId(string $affectation_igv_type_id): Configuration
        {
            $this->affectation_igv_type_id = (bool)$affectation_igv_type_id;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function getIncludeIgv(): ?bool
        {
            return (bool)$this->include_igv;
        }

        /**
         * @param bool|null $include_igv
         *
         * @return Configuration
         */
        public function setIncludeIgv(?bool $include_igv): Configuration
        {
            $this->include_igv = (bool)$include_igv;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getPercentageAllowanceCharge(): ?float
        {
            return $this->percentage_allowance_charge;
        }

        /**
         * @param float|null $percentage_allowance_charge
         *
         * @return Configuration
         */
        public function setPercentageAllowanceCharge(?float $percentage_allowance_charge): Configuration
        {
            $this->percentage_allowance_charge = $percentage_allowance_charge;
            return $this;
        }

        /**
         * @return float
         */
        public function getIgvRetentionPercentage(): float
        {
            return $this->igv_retention_percentage;
        }

        /**
         * @param float $igv_retention_percentage
         *
         * @return Configuration
         */
        public function setIgvRetentionPercentage(float $igv_retention_percentage): Configuration
        {
            $this->igv_retention_percentage = $igv_retention_percentage;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function getActiveAllowanceCharge(): ?bool
        {
            return (bool)$this->active_allowance_charge;
        }

        /**
         * @param bool|null $active_allowance_charge
         *
         * @return Configuration
         */
        public function setActiveAllowanceCharge(?bool $active_allowance_charge): Configuration
        {
            $this->active_allowance_charge = (bool)$active_allowance_charge;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function getActiveWarehousePrices(): ?bool
        {
            return (bool)$this->active_warehouse_prices;
        }

        /**
         * @param bool|null $active_warehouse_prices
         *
         * @return Configuration
         */
        public function setActiveWarehousePrices(?bool $active_warehouse_prices): Configuration
        {
            $this->active_warehouse_prices = (bool)$active_warehouse_prices;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function getProductOnlyLocation(): ?bool
        {
            return (bool)$this->product_only_location;
        }

        /**
         * @param bool|null $product_only_location
         *
         * @return Configuration
         */
        public function setProductOnlyLocation(?bool $product_only_location): Configuration
        {
            $this->product_only_location = (bool)$product_only_location;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getTermsCondition(): ?string
        {
            return $this->terms_condition;
        }

        /**
         * @param string|null $terms_condition
         *
         * @return Configuration
         */
        public function setTermsCondition(?string $terms_condition): Configuration
        {
            $this->terms_condition = $terms_condition;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getTermsConditionSale(): ?string
        {
            return $this->terms_condition_sale;
        }

        /**
         * @param string|null $terms_condition_sale
         *
         * @return Configuration
         */
        public function setTermsConditionSale(?string $terms_condition_sale): Configuration
        {
            $this->terms_condition_sale = $terms_condition_sale;
            return $this;
        }

        /**
         * @return bool
         */
        public function isCotizactionFinance(): ?bool
        {
            return (bool)$this->cotizaction_finance;
        }

        /**
         * @param bool|null $cotizaction_finance
         *
         * @return Configuration
         */
        public function setCotizactionFinance(?bool $cotizaction_finance): Configuration
        {
            $this->cotizaction_finance = (bool)$cotizaction_finance;
            return $this;
        }

        /**
         * @return bool
         */
        public function isQuotationAllowSellerGenerateSale(): ?bool
        {
            return (bool)$this->quotation_allow_seller_generate_sale;
        }

        /**
         * @param bool|null $quotation_allow_seller_generate_sale
         *
         * @return Configuration
         */
        public function setQuotationAllowSellerGenerateSale(?bool $quotation_allow_seller_generate_sale): Configuration
        {
            $this->quotation_allow_seller_generate_sale = (bool)$quotation_allow_seller_generate_sale;
            return $this;
        }

        /**
         * @return bool
         */
        public function isAllowEditUnitPriceToSeller(): ?bool
        {
            return (bool)$this->allow_edit_unit_price_to_seller;
        }

        /**
         * @param bool|null $allow_edit_unit_price_to_seller
         *
         * @return Configuration
         */
        public function setAllowEditUnitPriceToSeller(?bool $allow_edit_unit_price_to_seller): Configuration
        {
            $this->allow_edit_unit_price_to_seller = (bool)$allow_edit_unit_price_to_seller;
            return $this;
        }

        /**
         * @return bool
         */
        public function isLegendFooter(): ?bool
        {
            return (bool)$this->legend_footer;
        }

        /**
         * @param bool|null $legend_footer
         *
         * @return Configuration
         */
        public function setLegendFooter(?bool $legend_footer): Configuration
        {
            $this->legend_footer = (bool)$legend_footer;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getHeaderImage(): ?string
        {
            return $this->header_image;
        }

        /**
         * @param string|null $header_image
         *
         * @return Configuration
         */
        public function setHeaderImage(?string $header_image): Configuration
        {
            $this->header_image = $header_image;
            return $this;
        }

        /**
         * @return bool
         */
        public function isDestinationSale(): ?bool
        {
            return (bool)$this->destination_sale;
        }

        /**
         * @param bool|null $destination_sale
         *
         * @return Configuration
         */
        public function setDestinationSale(?bool $destination_sale): Configuration
        {
            $this->destination_sale = (bool)$destination_sale;
            return $this;
        }

        /**
         * @return bool
         */
        public function isDefaultDocumentType03(): ?bool
        {
            return (bool)$this->default_document_type_03;
        }

        /**
         * @param bool|null $default_document_type_03
         *
         * @return Configuration
         */
        public function setDefaultDocumentType03(?bool $default_document_type_03): Configuration
        {
            $this->default_document_type_03 = (bool)$default_document_type_03;
            return $this;
        }

        /**
         * @return bool
         */
        public function isDefaultDocumentType80(): ?bool
        {
            return (bool)$this->default_document_type_80;
        }

        /**
         * @param bool|null $default_document_type_80
         *
         * @return Configuration
         */
        public function setDefaultDocumentType80(?bool $default_document_type_80): Configuration
        {
            $this->default_document_type_80 = (bool)$default_document_type_80;
            return $this;
        }

        /**
         * @return bool
         */
        public function isSearchItemByBarcode(): ?bool
        {
            return (bool)$this->search_item_by_barcode;
        }

        /**
         * @param bool|null $search_item_by_barcode
         *
         * @return Configuration
         */
        public function setSearchItemByBarcode(?bool $search_item_by_barcode): Configuration
        {
            $this->search_item_by_barcode = (bool)$search_item_by_barcode;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getLogin(): ?string
        {
            return $this->login;
        }

        /**
         * @param string|null $login
         *
         * @return Configuration
         */
        public function setLogin(?string $login): Configuration
        {
            $this->login = $login;
            return $this;
        }

        /**
         * @return string
         */
        public function getNavbar(): string
        {
            return $this->navbar;
        }

        /**
         * @param string $navbar
         *
         * @return Configuration
         */
        public function setNavbar(string $navbar): Configuration
        {
            $this->navbar = $navbar;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getFinances(): ?string
        {
            return $this->finances;
        }

        /**
         * @param string|null $finances
         *
         * @return Configuration
         */
        public function setFinances(?string $finances): Configuration
        {
            $this->finances = $finances;
            return $this;
        }

        /**
         * @return bool
         */
        public function isTicket58(): ?bool
        {
            return (bool)$this->ticket_58;
        }

        /**
         * @param bool|null $ticket_58
         *
         * @return Configuration
         */
        public function setTicket58(?bool $ticket_58): Configuration
        {
            $this->ticket_58 = (bool)$ticket_58;
            return $this;
        }

        /**
         * @return bool
         */
        public function isSellerCanCreateProduct(): ?bool
        {
            return (bool)$this->seller_can_create_product;
        }

        /**
         * @param bool|null $seller_can_create_product
         *
         * @return Configuration
         */
        public function setSellerCanCreateProduct(?bool $seller_can_create_product): Configuration
        {
            $this->seller_can_create_product = (bool)$seller_can_create_product;
            return $this;
        }

        /**
         * @return bool
         */
        public function isSellerCanViewBalance(): ?bool
        {
            return (bool)$this->seller_can_view_balance;
        }

        /**
         * @param bool|null $seller_can_view_balance
         *
         * @return Configuration
         */
        public function setSellerCanViewBalance(?bool $seller_can_view_balance): Configuration
        {
            $this->seller_can_view_balance = (bool)$seller_can_view_balance;
            return $this;
        }

        /**
         * @return bool
         */
        public function isGroupItemsGenerateDocument(): ?bool
        {
            return (bool)$this->group_items_generate_document;
        }

        /**
         * @param bool|null $group_items_generate_document
         *
         * @return Configuration
         */
        public function setGroupItemsGenerateDocument(?bool $group_items_generate_document): Configuration
        {
            $this->group_items_generate_document = (bool)$group_items_generate_document;
            return $this;
        }

        /**
         * @return bool
         */
        public function isChangeFreeAffectationIgv(): ?bool
        {
            return (bool)$this->change_free_affectation_igv;
        }

        /**
         * @param bool|null $change_free_affectation_igv
         *
         * @return Configuration
         */
        public function setChangeFreeAffectationIgv(?bool $change_free_affectation_igv): Configuration
        {
            $this->change_free_affectation_igv = (bool)$change_free_affectation_igv;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function isSelectAvailablePriceList(): ?bool
        {
            return (bool)$this->select_available_price_list;
        }

        /**
         * @param bool|null $select_available_price_list
         *
         * @return Configuration
         */
        public function setSelectAvailablePriceList(?bool $select_available_price_list): Configuration
        {
            $this->select_available_price_list = (bool)$select_available_price_list;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function isNameProductPdfToXml(): ?bool
        {
            return (bool)$this->name_product_pdf_to_xml;
        }

        /**
         * @param bool|null $name_product_pdf_to_xml
         *
         * @return Configuration
         */
        public function setNameProductPdfToXml(?bool $name_product_pdf_to_xml): Configuration
        {
            $this->name_product_pdf_to_xml = (bool)$name_product_pdf_to_xml;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function isAutoPrint(): ?bool
        {
            return (bool)$this->auto_print;
        }

        /**
         * @param bool|null $auto_print
         *
         * @return Configuration
         */
        public function setAutoPrint(?bool $auto_print): Configuration
        {
            $this->auto_print = (bool)$auto_print;
            return $this;
        }

        /**
         * Si esta activo, permite listar los servicios en POS
         *
         * @return bool
         */
        public function isShowServiceOnPos(): bool
        {
            return (bool)$this->show_service_on_pos;
        }

        /**
         * @param bool $show_service_on_pos
         *
         * @return Configuration
         */
        public function setShowServiceOnPos(bool $show_service_on_pos): Configuration
        {
            $this->show_service_on_pos = (bool)$show_service_on_pos;
            return $this;
        }

        /**
         * @return bool
         */
        public function isPosHistory(): bool
        {
            return (bool)$this->pos_history;
        }

        /**
         * @return bool
         */
        public function isPosCostPrice(): bool
        {
            return (bool)$this->pos_cost_price;
        }

        /**
         * @return bool
         */
        public function isShowTotalsOnCpeList(): bool
        {
            return (bool)$this->show_totals_on_cpe_list;
        }

        /**
         * @param bool $show_totals_on_cpe_list
         *
         * @return Configuration
         */
        public function setShowTotalsOnCpeList(?bool $show_totals_on_cpe_list): Configuration
        {
            $this->show_totals_on_cpe_list = (bool)$show_totals_on_cpe_list;
            return $this;
        }

        /**
         * @return bool
         */
        public function isMiTiendaPe(): bool
        {
            return (bool)$this->mi_tienda_pe;
        }

        /**
         * @param bool $mi_tienda_pe
         *
         * @return Configuration
         */
        public function setMiTiendaPe(bool $mi_tienda_pe = false): Configuration
        {
            $this->mi_tienda_pe = (bool)$mi_tienda_pe;
            return $this;
        }

        /**
         * Permite usar configuracion personalizada del token de apiperu
         * @return bool
         */
        public function UseCustomApiPeruToken(){
            // .env ALLOW_CLIENT_USE_OWN_APIPERU_TOKEN
            return (bool)env('ALLOW_CLIENT_USE_OWN_APIPERU_TOKEN', false);
            return (bool)\Config('extra.AllowClientUseOwnApiperuToken');
        }

        /**
         * @return int|null
         */
        public function getNewValidatorPagination(): ?int
        {
            $val = (int)$this->new_validator_pagination;
            return $val==0?(int)config('tenant.items_per_page'):$val;
        }

        /**
         * @param int|null $new_validator_pagination
         *
         * @return CatItemSize
         */
        public function setNewValidatorPagination(?int $new_validator_pagination): CatItemSize
        {
            $this->new_validator_pagination = (int)$new_validator_pagination;
            return $this;
        }

        public function scopeGetUnitPriceDispatchRelatedRecord($query)
        {
            return $query->select('set_unit_price_dispatch_related_record')->first()->set_unit_price_dispatch_related_record;
        }

        /**
         * Usado en:
         * LegendInput, para facturas y boletas
         *
         * @return bool
         */
        public static function isEnabledLegendForestToXml()
        {
            return Configuration::select('legend_forest_to_xml')->firstOrFail()->legend_forest_to_xml;
        }

        /**
         *
         * Obtener configuracion avanzada de busqueda
         *
         * @param Builder $query
         * @return Builder
         */
        public function scopeIsEnabledAdvancedRecordsSearch($query)
        {
            return $query->select('enabled_advanced_records_search')->firstOrFail()->enabled_advanced_records_search;
        }


        /**
         *
         * Obtener configuracion de decimales para el precio unitario en pdf
         *
         * @param Builder $query
         * @return Builder
         */
        public function scopeGetDataDecimalQuantity($query)
        {
            return $query->select('change_decimal_quantity_unit_price_pdf', 'decimal_quantity_unit_price_pdf')->firstOrFail();
        }

        public function top_menu_a()
        {
            return $this->belongsTo(ModuleLevel::class, 'top_menu_a_id');
        }

        public function top_menu_b()
        {
            return $this->belongsTo(ModuleLevel::class, 'top_menu_b_id');
        }

        public function top_menu_c()
        {
            return $this->belongsTo(ModuleLevel::class, 'top_menu_c_id');
        }

        public function top_menu_d()
        {
            return $this->belongsTo(ModuleLevel::class, 'top_menu_d_id');
        }

        public function skin()
        {
            return $this->belongsTo(Skin::class, 'skin_id');
        }

        public function  getFacturaloConfig(): bool
        {
            return (bool) \Config('extra.suscription_facturalo');
        }
    }
