<?php

namespace App\Models\Tenant;

use App\Notifications\Tenant\PasswordResetNotification;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\DocumentaryProcedure\Models\DocumentaryFile;
use Modules\Expense\Models\Expense;
use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Models\Income;
use Modules\Inventory\Models\Devolution;
use Modules\LevelAccess\Models\ModuleLevel;
use Modules\Order\Models\OrderForm;
use Modules\Order\Models\OrderNote;
use Modules\Purchase\Models\FixedAssetPurchase;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseQuotation;
use Modules\Sale\Models\Contract;
use Modules\Sale\Models\SaleOpportunity;
use Modules\Sale\Models\TechnicalService;
use Modules\Sale\Models\UserCommission;
use App\Models\Tenant\Configuration;
use Modules\Restaurant\Models\RestaurantRole;


/**
 * Class User
 *
 * @package App\Models\Tenant
 * @mixin Model
 * @mixin Authenticatable
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $api_token
 * @property int|null $establishment_id
 * @property string $type
 * @property bool $permission_edit_cpe
 * @property bool $recreate_documents
 * @property bool $locked
 * @property string|null $identity_document_type_id
 * @property string|null $number
 * @property string|null $address
 * @property string|null $telephone
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $document_id
 * @property int|null $series_id
 * @property string|null $email_verified_at
 * @property string|null $phone
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\Document[] $documents
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\Document[] $seller_documents
 * @property int|null $documents_count
 * @property \App\Models\Tenant\Establishment $establishment
 * @property \Illuminate\Database\Eloquent\Collection|ModuleLevel[] $levels
 * @property int|null $levels_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\Module[] $modules
 * @property int|null $modules_count
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property int|null $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\SaleNote[] $sale_notes
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\SaleNote[] $seller_sale_notes
 * @property int|null $sale_notes_count
 * @property UserCommission|null $user_commission
 * @property Collection|Cash[] $cashes
 * @property Collection|Contract[] $contracts
 * @property Collection|Devolution[] $devolutions
 * @property Collection|Dispatch[] $dispatches
 * @property Collection|DocumentaryFile[] $documentary_files
 * @property Collection|Document[] $documents_where_seller
 * @property Collection|Expense[] $expenses
 * @property Collection|FixedAssetPurchase[] $fixed_asset_purchases
 * @property Collection|GlobalPayment[] $global_payments
 * @property Collection|Income[] $incomes
 * @property Collection|ItemsRating[] $items_ratings
 * @property Collection|OrderForm[] $order_forms
 * @property Collection|OrderNote[] $order_notes
 * @property Collection|Perception[] $perceptions
 * @property Collection|PurchaseOrder[] $purchase_orders
 * @property Collection|PurchaseQuotation[] $purchase_quotations
 * @property Collection|PurchaseSettlement[] $purchase_settlements
 * @property Collection|Purchase[] $purchases
 * @property Collection|Quotation[] $quotations
 * @property Collection|Retention[] $retentions
 * @property Collection|SaleOpportunity[] $sale_opportunities
 * @property Collection|Summary[] $summaries
 * @property Collection|TechnicalService[] $technical_services
 * @property Collection|Voided[] $voideds
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereApiToken($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereTypeUser()
 * @method static Builder|User whereUpdatedAt($value)
 * @property int|null $seller_documents_count
 * @property int|null $seller_sale_notes_count
 * @method static Builder|User getSellers($withEstablishment = true)
 * @method static Builder|User getWorkers()
 * @property int|null $cashes_count
 * @property int|null $contracts_count
 * @property int|null $devolutions_count
 * @property int|null $dispatches_count
 * @property int|null $documentary_files_count
 * @property int|null $documents_where_seller_count
 * @property int|null $expenses_count
 * @property int|null $fixed_asset_purchases_count
 * @property int|null $global_payments_count
 * @property int|null $incomes_count
 * @property int|null $items_ratings_count
 * @property int|null $order_forms_count
 * @property int|null $order_notes_count
 * @property int|null $perceptions_count
 * @property int|null $purchase_orders_count
 * @property int|null $purchase_quotations_count
 * @property int|null $purchase_settlements_count
 * @property int|null $purchases_count
 * @property int|null $quotations_count
 * @property int|null $retentions_count
 * @property int|null $sale_opportunities_count
 * @property int|null $summaries_count
 * @property int|null $technical_services_count
 * @property Collection|UserCommission[] $user_commissions
 * @property int|null $user_commissions_count
 * @property int|null $voideds_count
 * @property int|null $zone_id
 * @property int|null $restaurant_role_id

 */
class User extends Authenticatable
{
    use Notifiable;
    use UsesTenantConnection;

    protected $with = [
        'establishment'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'establishment_id',
        'type',
        'locked',
        'identity_document_type_id',
        'number',
        'address',
        'telephone',
        'document_id',
        'series_id',
        'permission_edit_cpe',
        'recreate_documents',
        'zone_id',
        'restaurant_role_id',
        
        'delete_payment',
        'create_payment',


        // 'email_verified_at',
        // 'api_token',
        // 'remember_token',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',

    ];

    protected $casts = [
        'series_id'=> 'int',
        'permission_edit_cpe' => 'boolean',
        'recreate_documents' => 'boolean',
        'establishment_id' => 'int',
        'zone_id' => 'int',
        'locked' => 'bool',

        'delete_payment' => 'bool',
        'create_payment' => 'bool',
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    public function levels()
    {
        return $this->belongsToMany(ModuleLevel::class);
    }

    public function authorizeModules($modules)
    {
        if ($this->hasAnyModule($modules)) {
            return true;
        }
        abort(401,
 'Esta acción no está autorizada.');
    }

    public function hasAnyModule($modules)
    {
        if (is_array($modules)) {
            foreach ($modules as $module)
            {
                if ($this->hasModule($module)) {
                    return true;
                }
            }
        } else {
            if ($this->hasModule($modules)) {
                return true;
            }
        }
        return false;
    }

    public function hasModule($module)
    {
        if ($this->modules()->where('name',
 $module)->first()) {
            return true;
        }
        return false;
    }



    public function getModule()
    {
        $module = $this->modules()->orderBy('id')->first();
        if ($module) {
            return $module->value;
        }
        return null;
    }

    public function getModules()
    {
        $modules = $this->modules()->get();
        if ($modules) {
            return $modules;
        }
        return null;
    }


    public function searchModule($module)
    {
        if ($this->modules()->where('value',
 $module)->first()) {
            return true;
        }
        return false;
    }


    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }


    /* *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /*
    public function inventory_transfer()
    {
        return $this->hasMany(InventoryTransfer::class);
    }
    */
    /**
     * @return HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function seller_documents()
    {
        return $this->hasMany(Document::class,
'seller_id',
'id');
    }

    public function sale_notes()
    {
        return $this->hasMany(SaleNote::class);
    }

    public function seller_sale_notes()
    {
        return $this->hasMany(SaleNote::class,
'seller_id',
'id');
    }

    public function restaurant_role()
    {
        return $this->belongsTo(RestaurantRole::class);
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('id',
 $user->id) : null;
    }



    public function getLevel()
    {
        $level = $this->levels()->orderBy('id')->first();
        if ($level) {
            return $level->value;
        }
        return null;
    }

    public function getLevels()
    {
        $levels = $this->levels()->get();
        if ($levels) {
            return $levels;
        }
        return null;
    }


    public function searchLevel($Level)
    {
        if ($this->levels()->where('value',
 $Level)->first()) {
            return true;
        }
        return false;
    }

    /**
     * @return HasOne
     */
    public function user_commission(): HasOne
    {
        return $this->hasOne(UserCommission::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     * @return mixed
     */
    public function getDocumentId() {
        return $this->document_id;
    }

    /**
     * @param mixed $document_id
     *
     * @return User
     */
    public function setDocumentId($document_id) {
        $this->document_id = $document_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeriesId() {
        return $this->series_id;
    }

    /**
     * @param mixed $series_id
     *
     * @return User
     */
    public function setSeriesId($series_id) {
        $this->series_id = $series_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return User
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Establece los niveles y modulos del usuario
     * @param array $modules
     * @param array $modules_levels
     *
     * @return $this
     */
    public function setModuleAndLevelModule($modules= [],
$modules_levels = []){
        $user_array = [
            'user_id' => $this->id,

        ];
        /*** Estableciendo los modulos */
        /** @var array $module_array */
        $module_array = $modules;

        $work = DB::connection('tenant')
                  ->table('module_user')
                  ->where($user_array);

        $deletes = $work
            ->whereNotIn('module_id',
 $module_array)
            ->delete();
        $total_modules = count($module_array);
        for ($i = 0; $i < $total_modules; $i++) {
            $item = (int)$module_array[$i];
            $module_ = $work
                ->where([
                            'module_id' => $item,

                        ])->first();
            if (empty($module_)) {
                $user_array['module_id'] = $item;
                $work->insert($user_array);
            }
        }
        unset($user_array['module_id']);

        $levels_array =$modules_levels;

        $work =DB::connection('tenant')
                 ->table('module_level_user')
                 ->where($user_array)
        ;
        $deletes = $work->whereNotIn('module_level_id',
 $levels_array)
                        ->delete();

        $total_modules_levels = count($levels_array);

        for ($i = 0; $i < $total_modules_levels; $i++) {
            $item = (int)$levels_array[$i];


            $module_ = $work
                ->where([
                            'module_level_id' => $item,

                        ])->first();
            if (empty($module_)) {
                $user_array['module_level_id'] = $item;
                $work->insert($user_array);
            }
        }
        return $this;
    }

    /**
     * Obtiene los niveles de modulo definidos por tenant
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrentModuleLevelByTenant(){
        return  DB::connection('tenant')
                      ->table('module_level_user')
                      ->select('module_level_id')
                      ->where('user_id',
 $this->id)
                      ->get();

    }
    /**
     * Obtiene los modulo definidos por tenant
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrentModuleByTenant(){
        return  DB::connection('tenant')
                  ->table('module_user')
                  ->select('module_id')
                  ->where('user_id',
 $this->id)
                  ->get();

    }

    /**
     * Devuelve una lista de usuarios vendedores junto con el usuario actual.
     * Si $withEstablishment es verdadero,
 * devuelve usuarios con establecimientos asignados carlomagno83/facturadorpro4#627
     * Si $withEstablishment es falso,
 * devuelve usuarios sin establecimientos asignados carlomagno83/facturadorpro4#233
     *
     * @param \Illuminate\Database\Query\Builder|Builder $query
     * @param bool                                       $withEstablishment
     *
     * @return \Illuminate\Database\Query\Builder|Builder
     */
    public function scopeGetSellers(  $query,
$withEstablishment = true){
        if($withEstablishment == false) {
            $query->without(['establishment']);
        }else{
            $query->with(['establishment']);

        }
        $query->whereIn('type',
 ['seller']);
        $query->orWhere('id',
 auth()->user()->id);
        return  $query;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeGetWorkers($query){
        $query->whereIn('type',
 ['seller',
'admin']);
        return  $query;
    }

    /**
     * Devuelve verdadero si el usuario es Admin.
     * @return bool
     */
    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    /**
     * Genera un token al azar de $length caracteres
     * @param int|null $length
     * @return $this
     */
    public function updateToken($length = 60)
    {
        $this->api_token = Str::random($length);
        return $this;
    }

    /**
     * @return array
     */
    public function getCollectionData(){
        $type = '';
        switch ($this->type) {
            case 'admin':
                $type =  'Administrador' ;
                break;
            case 'seller':
                $type =  'Vendedor' ;
                break;
            case 'client':
                $type =  'Cliente' ;
                break;
            default:
                # code...
                break;
        }

        return [
            'id' => $this->id,

            'email' => $this->email,

            'name' => $this->name,

            'api_token' => $this->api_token,

            'document_id' => $this->document_id,

            'serie_id' => ($this->series_id == 0)?null:$this->series_id,

            'establishment_description' => optional($this->establishment)->description,

            'type' => $type,

            'locked' => (bool) $this->locked,

        ];
    }

    /**
     * @return array
     */
    public function getCollectionRestaurantData(){
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'restaurant_role_id' => $this->restaurant_role_id,
            'restaurant_role_name' => $this->restaurant_role_id ? $this->restaurant_role->name : '',
            'locked' => (bool) $this->locked,
        ];
    }

    /**
     * @return HasMany
     */
    public function cashes()
    {
        return $this->hasMany(Cash::class);
    }

    /**
     * @return HasMany
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * @return HasMany
     */
    public function devolutions()
    {
        return $this->hasMany(Devolution::class);
    }

    /**
     * @return HasMany
     */
    public function dispatches()
    {
        return $this->hasMany(Dispatch::class);
    }

    /**
     * @return HasMany
     */
    public function documentary_files()
    {
        return $this->hasMany(DocumentaryFile::class);
    }

    /**
     * @return HasMany
     */
    public function documents_where_seller()
    {
        return $this->hasMany(Document::class, 'seller_id');
    }

    /**
     * @return HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * @return HasMany
     */
    public function fixed_asset_purchases()
    {
        return $this->hasMany(FixedAssetPurchase::class);
    }

    /**
     * @return HasMany
     */
    public function global_payments()
    {
        return $this->hasMany(GlobalPayment::class);
    }

    /**
     * @return HasMany
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    /**
     * @return HasMany
     */
    public function items_ratings()
    {
        return $this->hasMany(ItemsRating::class);
    }

    /**
     * @return HasMany
     */
    public function order_forms()
    {
        return $this->hasMany(OrderForm::class);
    }

    /**
     * @return HasMany
     */
    public function order_notes()
    {
        return $this->hasMany(OrderNote::class);
    }

    /**
     * @return HasMany
     */
    public function perceptions()
    {
        return $this->hasMany(Perception::class);
    }

    /**
     * @return HasMany
     */
    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * @return HasMany
     */
    public function purchase_quotations()
    {
        return $this->hasMany(PurchaseQuotation::class);
    }

    /**
     * @return HasMany
     */
    public function purchase_settlements()
    {
        return $this->hasMany(PurchaseSettlement::class);
    }

    /**
     * @return HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * @return HasMany
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * @return HasMany
     */
    public function retentions()
    {
        return $this->hasMany(Retention::class);
    }

    /**
     * @return HasMany
     */
    public function sale_opportunities()
    {
        return $this->hasMany(SaleOpportunity::class);
    }

    /**
     * @return HasMany
     */
    public function summaries()
    {
        return $this->hasMany(Summary::class);
    }

    /**
     * @return HasMany
     */
    public function technical_services()
    {
        return $this->hasMany(TechnicalService::class);
    }

    /**
     * @return HasMany
     */
    public function user_commissions()
    {
        return $this->hasMany(UserCommission::class);
    }

    /**
     * @return HasMany
     */
    public function voideds()
    {
        return $this->hasMany(Voided::class);
    }

    /**
     * Devuelve las series que puede seleccionar el usuario.
     *
     * @return Series[]|Builder[]|Collection|\Illuminate\Support\Collection
     */
    public function getSeries(){

        $document_id =  $this->document_id;
        $series_id =  $this->series_id;
        $establishment_id =  $this->establishment_id;
        $userType = $this->type;

        return  Series::FilterSeries($establishment_id)
            ->get()
            ->transform(function($row) use($document_id,$series_id,$userType) {
            /** @var Series $row */
            return $row->getCollectionData($document_id,$series_id,$userType);
        })->where('disabled',false);
    }

    /**
     * @return BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    /**
     * Devuelve una coleccion de usuarios vendedores para CPE y NV
     * @param int $establishment_id
     * @param int $userId
     *
     * @return User[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function  getSellersToNvCpe($establishment_id =0,$userId=0){
        return  self::where('establishment_id',$establishment_id)
            ->whereIn('type', ['seller', 'admin'])->orWhere('id', $userId)
            ->get();

    }

        
    /**
     * 
     * Validar si aplica el filtro por vendedor para el usuario en sesión (filtrar clientes por vendedor asignado)
     *
     * Usado en:
     * Person - scopeWhereFilterCustomerBySeller
     * 
     * @return bool
     */
    public function applyCustomerFilterBySeller()
    {
        $configuration = Configuration::select('customer_filter_by_seller')->first();

        return ($this->type === 'seller' && $configuration->customer_filter_by_seller);
    }

    
    /**
     * 
     * Obtener permisos para pagos de comprobantes
     *
     * @return array
     */
    public function getPermissionsPayment()
    {
        return [
            'create_payment' => $this->create_payment,
            'delete_payment' => $this->delete_payment,
        ];
    }

}
