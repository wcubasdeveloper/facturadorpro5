<?php

namespace App\Models\Tenant;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CatColorsItem;
use App\Models\Tenant\Catalogs\CatItemMoldCavity;
use App\Models\Tenant\Catalogs\CatItemMoldProperty;
use App\Models\Tenant\Catalogs\CatItemPackageMeasurement;
use App\Models\Tenant\Catalogs\CatItemProductFamily;
use App\Models\Tenant\Catalogs\CatItemStatus;
use App\Models\Tenant\Catalogs\CatItemUnitBusiness;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Modules\Account\Models\Account;
use Modules\Digemid\Models\CatDigemid;
use Modules\Inventory\Helpers\InventoryValuedKardex;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\Brand;
use Modules\Item\Models\Category;
use Modules\Item\Models\ItemLot;
use Modules\Item\Models\ItemLotsGroup;
use Modules\Item\Models\WebPlatform;
use Picqer\Barcode\BarcodeGeneratorPNG;


/**
 * Class Item
 *
 * @package App\Models\Tenant
 * @mixin ModelTenant
 * @method static Builder|Item newModelQuery()
 * @method static Builder|Item newQuery()
 * @method static Builder|Item pharmacy()
 * @method static Builder|Item query()
 * @method static Builder|Item whereFilterValuedKardex($params)
 * @method static Builder|Item whereHasInternalId()
 * @method static Builder|Item whereIsActive()
 * @method static Builder|Item whereIsNotActive()
 * @method static Builder|Item whereIsSet()
 * @method static Builder|Item whereNotIsSet()
 * @method static Builder|Item ForProduction()
 * @method static Builder|Item whereNotService()
 * @method static Builder|Item whereService()
 * @method static Builder|Item whereTypeUser()
 * @method static Builder|Item ProductSupply()
 * @method static Builder|Item ProductEnded()
 * @method static Builder|Item whereWarehouse()
 * @property \Illuminate\Database\Eloquent\Collection|ItemMovementRelExtra[] $item_movement_rel_extras
 * @property Account $account
 * @property Brand $brand
 * @property Bool $is_for_production
 * @property CatDigemid|null $cat_digemid
 * @property Category $category
 * @property CurrencyType $currency_type
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\DispatchItem[] $dispatch_items
 * @property int|null $dispatch_items_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\DocumentItem[] $document_items
 * @property int|null $document_items_count
 * @property mixed $attributes
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\ItemImage[] $images
 * @property int|null $images_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\InventoryKardex[] $inventory_kardex
 * @property int|null $inventory_kardex_count
 * @property \Illuminate\Database\Eloquent\Collection|ItemLot[] $item_lots
 * @property int|null $item_lots_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\ItemMovementRelExtra[] $item_movement_rel_extra
 * @property int|null $item_movement_rel_extra_count
 * @property \App\Models\Tenant\ItemType $item_type
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\ItemUnitType[] $item_unit_types
 * @property int|null $item_unit_types_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\Kardex[] $kardex
 * @property int|null $kardex_count
 * @property \Illuminate\Database\Eloquent\Collection|ItemLot[] $lots
 * @property int|null $lots_count
 * @property \Illuminate\Database\Eloquent\Collection|ItemLotsGroup[] $lots_group
 * @property int|null $lots_group_count
 * @property AffectationIgvType $purchase_affectation_igv_type
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\PurchaseItem[] $purchase_item
 * @property int|null $purchase_item_count
 * @property AffectationIgvType $sale_affectation_igv_type
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\SaleNoteItem[] $sale_note_items
 * @property int|null $sale_note_items_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\ItemSet[] $sets
 * @property int|null $sets_count
 * @property SystemIscType $system_isc_type
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\ItemTag[] $tags
 * @property int|null $tags_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\TechnicalServiceItem[] $technical_service_item
 * @property int|null $technical_service_item_count
 * @property UnitType $unit_type
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\ItemWarehousePrice[] $warehousePrices
 * @property int|null $warehouse_prices_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\ItemWarehouse[] $warehouses
 * @property int|null $warehouses_count
 * @property WebPlatform $web_platform
 * @method  array getCollectionData()
 * @method static Builder|Item whereFilterValuedKardexFormatSunat($params)
* @property \Illuminate\Database\Eloquent\Collection|ItemSupply[] $supplies
* @property \Illuminate\Database\Eloquent\Collection|ItemSupply[] supplies_items

 */
class Item extends ModelTenant
{
    protected $with = ['item_type', 'unit_type', 'currency_type', 'warehouses','item_unit_types', 'tags','item_lots'];
    protected $fillable = [
        'warehouse_id',
        'name',
        'second_name',
        'description',
        'model',
        'technical_specifications',
        'item_type_id',
        'internal_id',
        'item_code',
        'item_code_gs1',
        'unit_type_id',
        'currency_type_id',
        'sale_unit_price',
        'purchase_unit_price',
        'has_isc',
        'system_isc_type_id',
        'percentage_isc',
        'suggested_price',

        'sale_affectation_igv_type_id',
        'purchase_affectation_igv_type_id',
        'calculate_quantity',
        'has_igv',

        'stock',
        'stock_min',
        'percentage_of_profit',

        'attributes',
        'has_perception',
        'percentage_perception',
        'image',
        'image_medium',
        'image_small',

        'account_id',
        'amount_plastic_bag_taxes',
        'date_of_due',
        'is_set',
        'sale_unit_price_set',
        'apply_store',
        'apply_restaurant',
        'brand_id',
        'category_id',
        'lot_code',
        'lots_enabled',
        'active',
        'line',
        'series_enabled',
        'purchase_has_igv',
        'web_platform_id',
        'has_plastic_bag_taxes',
        'barcode',
        'sanitary',
        'cod_digemid',
        'is_for_production',

        'purchase_percentage_isc',
        'purchase_system_isc_type_id',
        'purchase_has_isc',

        'subject_to_detraction',
        // 'warehouse_id'
    ];

    protected $casts = [
        'date_of_due' => 'date',
        'is_for_production' => 'bool',
        'purchase_has_isc' => 'bool',
        'subject_to_detraction' => 'bool',
    ];

    /**
     * @return string
     */
    public function getSanitary() {
        return $this->sanitary;
    }

    /**
     * @param string $sanitary
     *
     * @return Item
     */
    public function setSanitary($sanitary) {
        $this->sanitary = $sanitary;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodDigemid() {
        return $this->cod_digemid;
    }

    /**
     * @param string $cod_digemid
     *
     * @return Item
     */
    public function setCodDigemid($cod_digemid) {
        $this->cod_digemid = $cod_digemid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return Item
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /*protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', 1);
        });
    }*/

    public function getAttributesAttribute($value)
    {
        return (is_null($value))?null:json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value))?null:json_encode($value);
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return BelongsTo
     */
    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }

    /**
     * @return BelongsTo
     */
    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }
    
    /**
     * @return BelongsTo
     */
    public function purchase_system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'purchase_system_isc_type_id');
    }
    
    /**
     * @return HasMany
     */
    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    /**
     * Se usa en la relacion con el inventario kardex en modules/Inventory/Traits/InventoryTrait.php.
     * Tambien se debe tener en cuenta modules/Inventory/Providers/InventoryKardexServiceProvider.php y
     * app/Providers/KardexServiceProvider.php para la correcta gestion de kardex
     *
     * @return HasMany
     */
    public function inventory_kardex()
    {
        return $this->hasMany(InventoryKardex::class);
    }


    /**
     * @return HasOne
     */
    public function cat_digemid()
    {
        return $this->hasOne(CatDigemid::class);
    }

    /**
     * @return HasMany
     */
    public function purchase_item()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * @return HasMany
     */
    public function dispatch_items()
    {
        return $this->hasMany(DispatchItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function sale_affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'sale_affectation_igv_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function purchase_affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'purchase_affectation_igv_type_id');
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereWarehouse($query)
     {
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
        if ($warehouse) {
            return $query->whereHas('warehouses', function($query) use($warehouse) {
                            $query->where('warehouse_id', $warehouse->id);
                        })->orWhere('unit_type_id', 'ZZ');
        }
        return $query;
     }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $this->scopeWhereWarehouse($query) : null;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereNotIsSet($query)
    {
        return $query->where('is_set', false);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereIsActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereIsSet($query)
    {
        return $query->where('is_set', true);
    }


    /**
     * @param Builder $query
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopePharmacy($query){
        return $query
            ->whereNotNull('items.cod_digemid')
            ->select('items.*')
            ->join('cat_digemid','cat_digemid.item_id','=','items.id')
            ;
    }

    /**
     * @return int
     */
    public function getStockByWarehouse()
    {
        if(auth()->user())
        {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            if ($warehouse) {
                $item_warehouse = $this->warehouses->where('warehouse_id',$warehouse->id)->first();
                return ($item_warehouse) ? $item_warehouse->stock : 0;
            }
        }

        return 0;
    }

    /**
     * @return HasMany
     */
    public function warehouses()
    {
        return $this->hasMany(ItemWarehouse::class)->with('warehouse');
    }


    /**
     * @return HasMany
     */
    public function item_unit_types()
    {
        return $this->hasMany(ItemUnitType::class);
    }

    /**
     * @return HasMany
     */
    public function tags()
    {
        return $this->hasMany(ItemTag::class);
    }

    /**
     * @return HasMany
     */
    public function sets()
    {
        return $this->hasMany(ItemSet::class);
    }

    /**
     * @return BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault([
            'id' => '',
            'name' => ''
        ]);
    }

    /**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'id' => '',
            'name' => ''
        ]);
    }

    /**
     * @return HasMany
     */
    public function item_lots()
    {
        return $this->hasMany(ItemLot::class, 'item_id');
    }

    /**
     * @return MorphMany
     */
    public function lots()
    {
        return $this->morphMany(ItemLot::class, 'item_loteable');
    }

    /**
     * @return HasMany
     */
    public  function images()
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    /**
     * @return HasMany
     */
    public function lots_group()
    {
        return $this->hasMany(ItemLotsGroup::class, 'item_id');
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereNotService($query)
    {
        return $query->where('unit_type_id','!=', 'ZZ');
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereService($query)
    {
        return $query->where('unit_type_id', 'ZZ');
    }

    /**
     * @return HasMany
     */
    public  function document_items()
    {
        return $this->hasMany(DocumentItem::class, 'item_id');
    }

    /**
     * @return HasMany
     */
    public  function sale_note_items()
    {
        return $this->hasMany(SaleNoteItem::class, 'item_id');
    }

    /**
     * @param Builder                               $query
     * @param                                       $params
     *
     * @return Builder
     */
    public function scopeWhereFilterValuedKardex(Builder $query, $params)
    {
        /*
        $query->OrWhereHas('document_items', function ($q) use ($params) {
            $q->whereHas('document', function ($q1) use ($params) {
                $q1->whereStateTypeAccepted()
                    ->whereTypeUser()
                    ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
                if ($params->establishment_id) {
                    $q1->where('establishment_id', $params->establishment_id);
                }
            });
        });
        $query->OrWhereHas('sale_note_items', function ($q) use ($params) {
            $q->whereHas('sale_note', function ($q1) use ($params) {
                $q1->whereStateTypeAccepted()
                    ->whereNotChanged()
                    ->whereTypeUser()
                    ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
                if ($params->establishment_id) {
                    $q1->where('establishment_id', $params->establishment_id);
                }
            });

        });

        return $query;
        */
        // No selecciona corectamente los establecimeintos.
        if (property_exists($params,'establishment_id') && $params->establishment_id) {
        // if ($params->establishment_id) {
            return $query->with(['document_items' => function ($q) use ($params) {
                $q->whereHas('document', function ($q) use ($params) {
                    $q->whereStateTypeAccepted()
                        ->whereTypeUser()
                        ->whereBetween('date_of_issue', [$params->date_start, $params->date_end])
                        ->where('establishment_id', $params->establishment_id);
                });
            },
                'sale_note_items' => function ($q) use ($params) {
                    $q->whereHas('sale_note', function ($q) use ($params) {
                        $q->whereStateTypeAccepted()
                            ->whereNotChanged()
                            ->whereTypeUser()
                            ->whereBetween('date_of_issue', [$params->date_start, $params->date_end])
                            ->where('establishment_id', $params->establishment_id);
                    });
                }]);

        }

        return $query->with(['document_items' => function ($q) use ($params) {
            $q->whereHas('document', function ($q) use ($params) {
                $q->whereStateTypeAccepted()
                    ->whereTypeUser()
                    ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
            });
        },
            'sale_note_items' => function ($q) use ($params) {
                $q->whereHas('sale_note', function ($q) use ($params) {
                    $q->whereStateTypeAccepted()
                        ->whereNotChanged()
                        ->whereTypeUser()
                        ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
                });
            }]);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereIsNotActive($query)
    {
        return $query->where('active', false);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereHasInternalId($query)
    {
        return $query->where('internal_id','!=', null);
    }

    /**
     * @return BelongsTo
     */
    public function web_platform()
    {
        return $this->belongsTo(WebPlatform::class);
    }

    /**
     * @return HasMany
     */
    public function warehousePrices()
    {
        return $this->hasMany(ItemWarehousePrice::class, 'item_id')->select('id','item_id', 'price', 'warehouse_id');
    }

    public static function getSaleUnitPriceByWarehouse(Item $item, int $warehouseId): string
    {
        $warehousePrice = $item->warehousePrices->where('item_id', $item->id)
            ->where('warehouse_id', $warehouseId)
            ->first();

        $price = $warehousePrice->price ?? $item->sale_unit_price;
        return number_format($price, 4, ".", "");
    }

    /**
     * Devuelve la esuctura de item para los select correspondientes.
     *
     * @param \App\Models\Tenant\Warehouse|Warehouse $warehouse
     * @param false                                  $extended
     *
     * @return array
     */
    public function getFullDescription($warehouse, $extended = false) {

        $desc = ($this->internal_id) ? $this->internal_id.' - '.$this->description : $this->description;
        $category = ($this->category) ? "{$this->category->name}" : '';
        $brand = ($this->brand) ? "{$this->brand->name}" : '';
        if ($this->unit_type_id != 'ZZ') {
            if (isset($this['stock'])) {
                $warehouse_stock = number_format($this['stock'], 2);
            } else {
                $warehouse_stock = ($this->warehouses && $warehouse)
                    ?
                    number_format($this->warehouses->where('warehouse_id', $warehouse->id)->first()->stock, 2)
                    :
                    0;
            }
            $stock = ($this->warehouses && $warehouse) ? "{$warehouse_stock}" : '';
        } else {
            $stock = '';
        }
        if($extended == false) {
            $desc = "{$desc} - {$brand}";
        }else {
            $desc = "{$desc} - {$category} - {$brand}";
        }
        return [
            'full_description'      => $desc,
            'brand'                 => $brand,
            'category'              => $category,
            'stock'                 => $stock,
            'warehouse_description' => $warehouse->description,
        ];
    }

    /**
     * Devuelve la relacion con el almacen dado
     * @param int $warehouse_id
     *
     * @return HasMany
     */
    public function getCurrentItemWarehouse($warehouse_id){
        return $this->warehouses()->where('warehouse_id',$warehouse_id);
    }

    private function getLotsBySerie($warehouse, $series,  $search_item_by_series)
    {
        $lots = [];


        if($search_item_by_series){

            $lots = $this->item_lots()->where('has_sale', false)
                                ->where('warehouse_id', $warehouse->id)
                                ->where('series', $series)
                                ->take(1)
                                ->get();

        // dd($search_item_by_series, $lots, $this->item_lots);
        }

        return $lots;
    }

    /**
     * Devuelve un estandar de estructura para items.
     * Es utilizado en :
     * app/Http/Controllers/Tenant/DocumentController.php
     * modules/Order/Http/Controllers/OrderNoteController.php
     *
     * @param \App\Models\Tenant\Warehouse|Warehouse|null $warehouse
     * @param false                                       $with_lots_has_sale
     * @param false                                       $extended_description
 *
     * @return array
     */
    public function getDataToItemModal(
        $warehouse = null,
        $with_lots_has_sale = false,
        $extended_description = false,
        $series = null,
        $search_item_by_series = false,
        $aditional_data = true
    ) {

        if ($warehouse == null) {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
        }
        $detail = $this->getFullDescription($warehouse, $extended_description);
        $realtion_item_unit_types = $this->item_unit_types;
        $lots_grp = $this->lots_group;

        $lots = $this->getLotsBySerie($warehouse, $series, $search_item_by_series);
        $blank = [];
        $currentColors = collect($blank);
        $ItemUnitsPerPackage = collect($blank);
        $ItemMoldProperty = collect($blank);
        $ItemProductFamily = collect($blank);
        $ItemMoldCavity = collect($blank);
        $ItemPackageMeasurement = collect($blank);
        $ItemStatus = collect($blank);
        $ItemSize = collect($blank);
        $ItemUnitBusiness = collect($blank);

        if($aditional_data === true){
            $currentColors = $this->getItemColor()->transform(function ($row) {
                return $row->cat_colors_item_id;
            });
            $ItemUnitsPerPackage = $this->getItemUnitsPerPackage()->transform(function ($row) {
                return $row->cat_item_units_per_package_id;
            });
            $ItemMoldProperty = $this->getItemMoldProperty()->transform(function ($row) {
                return $row->cat_item_mold_properties_id;
            });


            $ItemProductFamily = $this->getItemProductFamily()->transform(function ($row) {
                return $row->cat_item_product_family_id;
            });
            $ItemMoldCavity = $this->getItemMoldCavity()->transform(function ($row) {
                return $row->cat_item_mold_cavities_id;
            });
            $ItemPackageMeasurement = $this->getItemPackageMeasurement()->transform(function ($row) {
                return $row->cat_item_package_measurements_id;
            });
            $ItemStatus = $this->getItemStatus()->transform(function ($row) {
                return $row->cat_item_status_id;
            });
            $ItemUnitBusiness = $this->getItemUnitBusiness()->transform(function ($row) {
                return $row->cat_item_unit_business_id;
            });

            $ItemSize = $this->getItemSize()->transform(function ($row) {
                return $row->cat_item_size_id;
            });

        }

        if ($with_lots_has_sale == true) {
            $lots = $this->item_lots->where('has_sale', false)->transform(function ($row) {
                return [
                    'id'           => $row->id,
                    'series'       => $row->series,
                    'date'         => $row->date,
                    'item_id'      => $row->item_id,
                    'warehouse_id' => $row->warehouse_id,
                    'has_sale'     => (bool)$row->has_sale,
                    'lot_code'     => ($row->item_loteable_type)
                        ?
                        (isset($row->item_loteable->lot_code)
                            ?
                            $row->item_loteable->lot_code
                            :
                            null)
                        :
                        null,
                ];
            })->values();
        }
        $stock = $detail['stock'];

        // Obtiene el stock basado el en almacen itemwarehouse
        $stockItemWarehouse = $this->getCurrentItemWarehouse($warehouse->id)->first();
        if(is_object($stockItemWarehouse)) {
            $stock = $stockItemWarehouse->stock;
        }

        $stockPerCategory = ItemMovement::getStockByCategory($this->id,auth()->user()->establishment_id);
        $currency = $this->currency_type;
        if(empty($currency )){
            $currency = new CurrencyType();
        }
        $data = [
            'id'                               => $this->id,
            'item_code'                    => $this->item_code,
            'full_description'                 => $detail['full_description'],
            'model'                            => $this->model,
            'brand'                            => $detail['brand'],
            'stock_by_extra'                            =>  $stockPerCategory,
            'warehouse_description'            => $detail['warehouse_description'],
            'extra'                         => collect([
                                                'colors' => null,
                                                'CatItemUnitsPerPackage' => null,
                                                'CatItemMoldProperty' => null,
                                                'CatItemProductFamily' => null,
                                                'CatItemMoldCavity' => null,
                                                'CatItemPackageMeasurement' => null,
                                                'CatItemStatus' => null,
                                                'CatItemSize' => null,
                                                'CatItemUnitBusiness' => null,
                                             ]),
            'category'                         => $detail['category'],
            'stock'                            => $stock,
            'internal_id'                      => $this->internal_id,
            'description'                      => $this->description,
            'currency_type_id'                 => $this->currency_type_id,
            'currency_type_symbol'             => $currency->symbol,
            'sale_unit_price'                  => self::getSaleUnitPriceByWarehouse($this, $warehouse->id),
            'purchase_unit_price'              => $this->purchase_unit_price,
            'unit_type_id'                     => $this->unit_type_id,
            'original_unit_type_id'                     => $this->unit_type_id,
            'sale_affectation_igv_type'     => $this->sale_affectation_igv_type,
            'sale_affectation_igv_type_id'     => $this->sale_affectation_igv_type_id,
            'purchase_affectation_igv_type_id' => $this->purchase_affectation_igv_type_id,
            'calculate_quantity'               => (bool)$this->calculate_quantity,
            'has_igv'                          => (bool)$this->has_igv,
            'has_plastic_bag_taxes'            => (bool)$this->has_plastic_bag_taxes,
            'amount_plastic_bag_taxes'         => $this->amount_plastic_bag_taxes,
            'colors' => $currentColors,
            'CatItemUnitsPerPackage' => $ItemUnitsPerPackage,
            'CatItemMoldProperty' => $ItemMoldProperty,
            'CatItemProductFamily' => $ItemProductFamily,
            'CatItemMoldCavity' => $ItemMoldCavity,
            'CatItemPackageMeasurement' => $ItemPackageMeasurement,
            'CatItemStatus' => $ItemStatus,
            'CatItemSize' => $ItemSize,
            'CatItemUnitBusiness' => $ItemUnitBusiness,
            'item_unit_types'                  => $this->item_unit_types->transform(function ($item_unit_types) {
                if(is_array($item_unit_types)){
                    return $item_unit_types;
                }
                return [
                    'id'            => $item_unit_types->id,
                    'description'   => "{$item_unit_types->description}",
                    'item_id'       => $item_unit_types->item_id,
                    'unit_type_id'  => $item_unit_types->unit_type_id,
                    'quantity_unit' => $item_unit_types->quantity_unit,
                    'price1'        => $item_unit_types->price1,
                    'price2'        => $item_unit_types->price2,
                    'price3'        => $item_unit_types->price3,
                    'price_default' => $item_unit_types->price_default,
                    'barcode' => $item_unit_types->barcode,
                ];
            }),
            'warehouses' => collect($this->warehouses)->transform(function ($warehouses) use ($warehouse) {
                return [
                    'warehouse_description' => $warehouses->warehouse->description,
                    'stock'                 => (!empty($warehouses->stock)) ? $warehouses->stock : 0,
                    'warehouse_id'          => $warehouses->warehouse_id,
                    'checked'               => ($warehouses->warehouse_id == $warehouse->id) ? true : false,
                ];
            }),
            // se listaran atributos necesarios en pdf de otra forma
            'attributes'     => $this->getAttributesAttribute($this->attributes['attributes']),
            'lots_group'     => collect($lots_grp)->transform(function ($lots_group) {
                return [
                    'id'          => $lots_group->id,
                    'code'        => $lots_group->code,
                    'quantity'    => $lots_group->quantity,
                    'date_of_due' => $lots_group->date_of_due,
                    'checked'     => false,
                    'compromise_quantity' => 0
                ];
            }),
            'lots'           => $lots,
            'lots_enabled'   => (bool)$this->lots_enabled,
            'series_enabled' => (bool)$this->series_enabled,
            'is_set'         => (bool)$this->is_set,

            'lot_code'    => $this->lot_code,
            'date_of_due' => $this->date_of_due,
            'barcode'     => $this->barcode,
            'change_free_affectation_igv'     => false,
            'original_affectation_igv_type_id'     => $this->sale_affectation_igv_type_id,

            'has_isc' => (bool)$this->has_isc,
            'system_isc_type_id' => $this->system_isc_type_id,
            'percentage_isc' => $this->percentage_isc,
            'is_for_production'=>$this->isIsForProduction(),
            'subject_to_detraction' => $this->subject_to_detraction,
            
        ];

        // El nombre de producto, por defecto, sera la misma descripcion.
        $data['name_product_pdf']="<p>".$data['description']."</p>";

        return $data;
    }

    /**
     * @return Model|\Illuminate\Database\Query\Builder|mixed|CatDigemid|object|null
     */
    public function getCatDigemid(){
        return CatDigemid::where('item_id',$this->id)->first();
    }

    /**
     * Devuelte el conjunto de tipos de igv que estan exonerados
     *
     * @return string[]
     */
    public static function AffectationIgvTypesExoneratedUnaffected(){
        return ['20', '21', '30', '31', '32', '33', '34', '35', '36', '37'];
    }

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @param Configuration|null $configuration
     *
     * @return array
     */
    public function getCollectionData(Configuration $configuration = null){
        if(empty($configuration)){
            $configuration =  Configuration::first();
        }
        $brand = null;
        if (!empty($this->brand_id)) {
            $brand = $this->brand()->first()->name;
        }
        $has_igv_description = null;
        $purchase_has_igv_description = null;

        $affectation_igv_types_exonerated_unaffected = self::AffectationIgvTypesExoneratedUnaffected();

        if (in_array($this->sale_affectation_igv_type_id, $affectation_igv_types_exonerated_unaffected)) {
            $has_igv_description = 'No';
        } else {
            $has_igv_description = ((bool)$this->has_igv) ? 'Si' : 'No';
        }

        if (in_array($this->purchase_affectation_igv_type_id, $affectation_igv_types_exonerated_unaffected)) {
            $purchase_has_igv_description = 'No';
        } else {
            $purchase_has_igv_description = ((bool)$this->purchase_has_igv) ? 'Si' : 'No';
        }
        $digemid_exportable = false;
        $name_disa = '';
        $laboratory = '';
        $currentColors = ItemColor::where('item_id', $this->id)->get()->transform(function ($row) {
            return $row->TransformDatatoEdit();
        });

        if($configuration->isPharmacy()) {
            $digemid = $this->getCatDigemid();
            if (!empty($digemid)) {
                $digemid_exportable = (bool)$digemid->active;
                $name_disa = $digemid->getNomProd();
                $laboratory = $digemid->getNomTitular();
            }
        }

        $decimal_units = (int)$configuration->decimal_quantity;
        $stockPerCategory = ItemMovement::getStockByCategory($this->id,auth()->user()->establishment_id);
        $has_igv = (bool)$this->has_igv;
        $igv = 1.18; // El igv es de 18%
        $affectation_igv_types_exonerated_unaffected = self::AffectationIgvTypesExoneratedUnaffected();
        if (in_array($this->sale_affectation_igv_type_id, $affectation_igv_types_exonerated_unaffected)) {
            // Exonerado, solo se multiplica por la unidad para que no haga cambio.
            $igv = 1;
        }
        $itemSupply = $this->supplies;
        if(!emptY($itemSupply)){
            $itemSupply = $itemSupply->transform(function (ItemSupply $row ){
                return $row-> getCollectionData();
            });
        }
        $salePriceWithIgv = ($has_igv == true)?$this->sale_unit_price:($this->sale_unit_price * $igv);
        $salePriceWithIgv = number_format($salePriceWithIgv, $configuration->decimal_quantity, '.', '');
        $currency = $this->currency_type;
        if(empty($currency )){
            $currency = new CurrencyType();
        }

        $show_sale_unit_price = "{$currency->symbol} {$this->getFormatSaleUnitPrice()}";

        return [
            'name_disa' => $name_disa,
            'laboratory' => $laboratory,
            'exportable_pharmacy' => $digemid_exportable,
            'colors' => $currentColors,
            'stock_by_extra' => $stockPerCategory,
            'id' => $this->id,
            'sanitary' => $this->sanitary,
            'cod_digemid' => $this->cod_digemid,
            'unit_type_id' => $this->unit_type_id,
            'unit_type_text' => $this->getUnitTypeText(),
            'description' => $this->description,
            'name' => $this->name,
            'second_name' => $this->second_name,
            'model' => $this->model,
            'barcode' => $this->barcode,
            'brand' => $brand,
            'category_description' => optional($this->category)->name,
            'warehouse_id' => $this->warehouse_id,
            'internal_id' => $this->internal_id,
            'item_code' => $this->item_code,
            'item_code_gs1' => $this->item_code_gs1,
            'stock' => $this->getStockByWarehouse(),
            'stock_min' => $this->stock_min,
            'currency_type_id' => $this->currency_type_id,
            'currency_type_symbol' => $currency->symbol,
            'sale_affectation_igv_type_id' => $this->sale_affectation_igv_type_id,
            'purchase_affectation_igv_type_id' => $this->purchase_affectation_igv_type_id,
            'amount_sale_unit_price' => $this->sale_unit_price,
            'calculate_quantity' => (bool)$this->calculate_quantity,
            'has_igv' => (bool)$this->has_igv,
            'active' => (bool)$this->active,
            'has_igv_description' => $has_igv_description,
            'purchase_has_igv_description' => $purchase_has_igv_description,
            'sale_unit_price' => $show_sale_unit_price,
            // 'sale_unit_price' => "{$currency->symbol} {$this->sale_unit_price}",
            'sale_unit_price_with_igv' => "{$currency->symbol} $salePriceWithIgv",
            'purchase_unit_price' => "{$currency->symbol} {$this->purchase_unit_price}",
            'created_at' => ($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : '',
            'updated_at' => ($this->created_at) ? $this->updated_at->format('Y-m-d H:i:s') : '',
            'warehouses' => collect($this->warehouses)->transform(function ($row) {
                return [
                    'warehouse_description' => $row->warehouse->description,
                    'stock' => $row->stock,
                ];
            }),
            'apply_store' => (bool)$this->apply_store,
            'apply_restaurant' => (bool)$this->apply_restaurant,
            'image_url' => ($this->image !== 'imagen-no-disponible.jpg')
                ? asset('storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $this->image)
                : asset("/logo/{$this->image}"),
            'image_url_medium' => ($this->image_medium !== 'imagen-no-disponible.jpg')
                ? asset('storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $this->image_medium)
                : asset("/logo/{$this->image_medium}"),
            'image_url_small' => ($this->image_small !== 'imagen-no-disponible.jpg')
                ? asset('storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $this->image_small)
                : asset("/logo/{$this->image_small}"),
            'tags' => $this->tags,
            'tags_id' => $this->tags->pluck('tag_id'),
            'item_unit_types' => $this->item_unit_types->transform(function ($row) use ($decimal_units) {
                /** @var ItemUnitType $row */
                return $row->getCollectionData($decimal_units);
                /** Movido al modelo */
                return [
                    'id' => $row->id,
                    'description' => "{$row->description}",
                    'item_id' => $row->item_id,
                    'unit_type_id' => $row->unit_type_id,
                    'quantity_unit' => number_format($this->quantity_unit, $configuration->decimal_quantity, '.', ''),
                    'price1' => number_format($this->price1, $configuration->decimal_quantity, '.', ''),
                    'price2' => number_format($this->price2, $configuration->decimal_quantity, '.', ''),
                    'price3' => number_format($this->price3, $configuration->decimal_quantity, '.', ''),
                    'price_default' => $this->price_default,
                ];
            }),
            'item_warehouse_prices' => $this->warehousePrices->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'item_id' => $row->item_id,
                    'warehouse_id' => $row->warehouse_id,
                    'price' => $row->price,
                    'description' => $row->warehouse->description,
                ];
            }),
            'is_for_production'=>$this->isIsForProduction(),
            'supplies' => $itemSupply,

        ];
    }

    
    /**
     * Obtener precio unitario entero o con decimales
     *
     * @return int|float
     */
    public function getFormatSaleUnitPrice()
    {
        return ((int)$this->sale_unit_price != $this->sale_unit_price) ? $this->sale_unit_price : round($this->sale_unit_price);
    }


    /**
     * Establece un standar para insersion por catalogo DIGEMID
     *
     * Este proviene del excel, debe tener la estructura :
     *
     * $Cod_Prod = $row[0];
     * $Nom_Prod = $row[1];
     * $Concent = $row[2];
     * $Nom_Form_Farm = $row[3];
     * $Nom_Form_Farm_Simplif = $row[4];
     * $Presentac = $row[5];
     * $Fracciones = $row[6];
     * $Fec_Vcto_Reg_Sanitario = $row[7];
     * $Num_RegSan = $row[8];
     * $Nom_Titular = $row[9];
    * $Situacion = $row[10];
     *
     * @param array $data
     *
     * @return $this
     */
    public function fillFormDigemid($data){

        $model = substr($data[5],0,100);
        $line = substr($data[4],0,255);

        $this->cod_digemid = $data[0];

        $active = 1;
        if(strtolower(trim($data[10])) !== 'act'){
            $active = 0;
        }
        $warehouse = auth()->user()->establishment_id;
        $today =  Carbon::now()->format('Y-m-d');
        $this
            ->setInArray('sanitary',$data[8])
            ->setInArray('internal_id',$data[0])
            ->setInArray('description',$data[1])
            ->setInArray('second_name',$data[1]." ".$data[2])
            ->setInArray('name', $data[3].' '. $data[1]." ".$data[2])
            ->setInArray('sale_affectation_igv_type_id',10)
            ->setInArray('purchase_affectation_igv_type_id',$this->sale_affectation_igv_type_id)
            ->setInArray('item_type_id','01')
            ->setInArray('barcode',$this->internal_id)
            ->setInArray('lot_code',$this->internal_id)
            ->setInArray('model',$model)
            ->setInArray('line',$line)
            ->setInArray('lots_enabled',true)
            ->setInArray('stock',0)
            ->setInArray('stock_min',0)
            ->setInArray('currency_type_id','PEN')
            ->setInArray('unit_type_id','NIU')
            ->setInArray('active',$active)
            ->setInArray('sale_unit_price',1)
            ->setInArray('sale_unit_price_set',null)
            ->setInArray('has_igv',true)
            ->setInArray('is_set',false)
            ->setInArray('purchase_has_igv',true)
            ->setInArray('amount_plastic_bag_taxes',0.1)
            ->setInArray('purchase_unit_price',0)
            ->setInArray('percentage_isc',0)
            ->setInArray('suggested_price',0)
            ->setInArray('has_plastic_bag_taxes',false)
            ->setInArray('has_isc',false)
            ->setInArray('has_plastic_bag_taxes',false)
            ->setInArray('warehouse_id',$warehouse)
            ->setInArray('image','imagen-no-disponible.jpg')
            ->setInArray('image_medium','imagen-no-disponible.jpg')
            ->setInArray('image_small','imagen-no-disponible.jpg')
            ->setInArray('date_of_due',$today)
            ->setInArray('item_code',$this->cod_digemid)
            ->setInArray('brand_id',null)
            ->setInArray('category_id',null)

        /*
        'technical_specifications',
        'item_code_gs1',
        'system_isc_type_id',
        'sale_affectation_igv_type_id',
        'purchase_affectation_igv_type_id',
        'calculate_quantity',
        'percentage_of_profit',
        'attributes',
        'has_perception',
        'percentage_perception',
        'account_id',
        'apply_store',
        'apply_restaurant',
        'series_enabled',
        'web_platform_id',
        */
        ;
        $this->description = substr($this->description,0,600);
        $this->second_name = substr($this->second_name,0,600);
        $this->name = substr($this->name,0,600);
        return $this;
    }

    /**
     * Si la propiedad es nula, establece el valor value
     * @param $property
     * @param $value
     *
     * @return $this
     */
    protected function setInArray($property,$value){

        if($this->{$property} == null){
            $this->{$property} = $value;
        }

        return $this;
    }

    /**
     * @param $cod
     *
     * @return Item|Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function FindByCodDigemid($cod){
        return self::where('cod_digemid',$cod)->first();

    }

    /**
     * Devuelve el modelo de WebPlatform Asociado
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model|mixed|WebPlatform|WebPlatform[]|null
     */
    public function getWebPlatformModel(){
        return WebPlatform::find($this->web_platform_id);
    }

    /**
     * @return ItemSet[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|Collection|mixed|null
     */
    public function getSetItems(){

        $is_set = (bool) $this->is_set;
        if($is_set){
            return  ItemSet::where('item_id',$this->id)->get();
        }
        return null;
    }

    /**
     * Devuelve una estructura en comun para el reporte Kardex
     * @return array
     */
    public function getReportValuedKardexCollection(){

        $values_records = InventoryValuedKardex::getValuesRecords($this->document_items, $this->sale_note_items);
        $quantity_sale = $values_records['quantity_sale'];
        $total_sales = $values_records['total_sales'];
        $item_cost = $quantity_sale * $this->purchase_unit_price;
        $valued_unit = $total_sales - $item_cost;
        $item = $this;
        return [
            'id' => $this->id,
            'item_description' => $this->description,
            'category_description' => optional($this->category)->name,
            'brand_description' => optional($this->brand)->name,
            'unit_type_id' => $this->unit_type_id,
            'quantity_sale' => number_format($quantity_sale, 2, ".", ""),
            'purchase_unit_price' => number_format($this->purchase_unit_price, 2, ".", ""),
            'total_sales' => number_format($total_sales, 2, ".", ""),
            'item_cost' => number_format($item_cost, 2, ".", ""),
            'valued_unit' => number_format($valued_unit, 2, ".", ""),
            'warehouses' => $this->warehouses->transform(function ($row, $key) use ($item) {
                return [
                    'id' => $row->id,
                    'stock' => $row->stock,
                    'warehouse_description' => $row->warehouse->description,
                    'description' => "{$row->warehouse->description} - {$row->stock}",
                    'sale_unit_price' => self::getSaleUnitPriceByWarehouse($item, $row->id),
                ];
            }),

        ];
    }


    /**
     * Almacena las unidades por almacen para poder guardarlos en la tabla.
     *
     * @param array $ItemUnitsPerPackage
     *
     * @return $this
     * @throws Exception
     */
    public function setItemUnitsPerPackage(array $ItemUnitsPerPackage = []) {

        return $this->setExtraData(ItemUnitsPerPackage::class,'cat_item_units_per_package_id',$ItemUnitsPerPackage);


    }

    public function getItemUnitsPerPackage(){
        return ItemUnitsPerPackage::where('item_id', $this->id)->where('active',1)->get();
    }
    /**
     * Almacena los colores para poder guardarlos en la tabla.
     * @param array $colors
     *
     * @return $this
     * @throws Exception
     */
    public function setItemColor($colors = []) {
        return $this->setExtraData(ItemColor::class,'cat_colors_item_id',$colors);
    }

    public function getItemColor(){
        return ItemColor::where('item_id', $this->id)->where('active',1)->get();
    }

    public function setItemMoldProperty($data = []) {
        return $this->setExtraData(ItemMoldProperty::class,'cat_item_mold_properties_id',$data);
    }
    public function getItemMoldProperty(){
        return ItemMoldProperty::where('item_id', $this->id)->where('active',1)->get();
    }
    public function setItemUnitBusiness($data = []) {
        return $this->setExtraData(ItemUnitBusiness::class,'cat_item_unit_business_id',$data);
    }

    public function getItemUnitBusiness(){
        return ItemUnitBusiness::where('item_id', $this->id)->where('active',1)->get();
    }
    public function setItemStatus($data = []) {
        return $this->setExtraData(ItemStatus::class,'cat_item_status_id',$data);
    }

    public function getItemStatus(){
        return ItemStatus::where('item_id', $this->id)->where('active',1)->get();
    }

    public function setItemPackageMeasurement($data = []) {
        return $this->setExtraData(ItemPackageMeasurement::class,'cat_item_package_measurements_id',$data);
    }

    public function getItemPackageMeasurement(){
        return ItemPackageMeasurement::where('item_id', $this->id)->where('active',1)->get();
    }
    public function setItemMoldCavity($data = []) {
        return $this->setExtraData(ItemMoldCavity::class,'cat_item_mold_cavities_id',$data);
    }

    public function getItemMoldCavity(){
        return ItemMoldCavity::where('item_id', $this->id)->where('active',1)->get();
    }
    public function setItemProductFamily($data = []) {
        return $this->setExtraData(ItemProductFamily::class,'cat_item_product_family_id',$data);
    }
    public function setItemSize($data = []) {
        return $this->setExtraData(ItemSize::class,'cat_item_size_id',$data);
    }

    public function getItemProductFamily(){
        return ItemProductFamily::where('item_id', $this->id)->where('active',1)->get();
    }
    public function getItemSize(){
        return ItemSize::where('item_id', $this->id)->where('active',1)->get();
    }

    /**
     * @param  ItemMoldProperty|ItemUnitBusiness|ItemStatus|ItemColor|ItemUnitsPerPackage|ItemProductFamily|ItemMoldCavity|ItemPackageMeasurement|null      $class
     * @param string $field_id
     * @param array  $data
     *
     * @return $this
     */
    protected function setExtraData($class,$field_id = '',$data = []): Item
    {
        $dataCollection = collect($data);
        $currentRow = $class::where('item_id',$this->id)
            ->whereNotIn($field_id,$dataCollection)
            ->get();

        foreach ($currentRow as $row){
            $row->setActive(false)->push();
            $row->delete();
        }



        foreach($dataCollection as $item){

            if($field_id=='cat_item_product_family_id' && get_class($item)==CatItemProductFamily::class) {
                $item = $item->id;
            }
            $ck = [
                'item_id'=>$this->id,
                $field_id=>$item
            ];
            $newRow = $class::where($ck)->first();

            if(empty($newRow)) {
                $newRow = new $class($ck);
            }

            $newRow->setActive(true)->push();
        }
        return $this;
    }
    /**
     * @param  ItemMoldProperty|ItemUnitBusiness|ItemStatus|ItemColor|ItemUnitsPerPackage|ItemProductFamily|ItemMoldCavity|ItemPackageMeasurement|null      $class
     * @param string $field_id
     * @param array  $data
     *
     * @return $this
     */
    public function setExtraDataByCatalogCategory($class,$field_id = '',$data = []){
        return $this->setExtraData($class, $field_id, $data);
    }
    /**
     * Genera una estructura unica para los datos extra.
     *
     * @param       $array
     * @param array $row
     */
    public static function SaveExtraDataToRequest(&$array, $row = [])
    {

        if(isset($row['item'], $row['item']['extra'])) {
            $extra = $row['item']['extra'];


            $colors_id = isset($extra['colors']) ? (int)$extra['colors'] : null;
            $CatItemUnitsPerPackage_id = isset($extra['CatItemUnitsPerPackage']) ? (int)$extra['CatItemUnitsPerPackage'] : null;
            $CatItemMoldProperty_id = isset($extra['CatItemMoldProperty']) ? (int)$extra['CatItemMoldProperty'] : null;
            $CatItemProductFamily_id = isset($extra['CatItemProductFamily']) ? (int)$extra['CatItemProductFamily'] : null;
            $CatItemMoldCavity_id = isset($extra['CatItemMoldCavity']) ? (int)$extra['CatItemMoldCavity'] : null;
            $CatItemPackageMeasurement_id = isset($extra['CatItemPackageMeasurement']) ? (int)$extra['CatItemPackageMeasurement'] : null;
            $CatItemStatus_id = isset($extra['CatItemStatus']) ? (int)$extra['CatItemStatus'] : null;
            $CatItemUnitBusiness_id = isset($extra['CatItemUnitBusiness']) ? (int)$extra['CatItemUnitBusiness'] : null;
            $CatItemSize_id = isset($extra['CatItemSize']) ? (int)$extra['CatItemSize'] : null;


            $array['item']['extra']["colors"] = $colors_id;
            $array['item']['extra']["CatItemUnitsPerPackage"] = $CatItemUnitsPerPackage_id;
            $array['item']['extra']["CatItemMoldProperty"] = $CatItemMoldProperty_id;
            $array['item']['extra']["CatItemProductFamily"] = $CatItemProductFamily_id;
            $array['item']['extra']["CatItemMoldCavity"] = $CatItemMoldCavity_id;
            $array['item']['extra']["CatItemPackageMeasurement"] = $CatItemPackageMeasurement_id;
            $array['item']['extra']["CatItemStatus"] = $CatItemStatus_id;
            $array['item']['extra']["CatItemUnitBusiness"] = $CatItemUnitBusiness_id;
            $array['item']['extra']["CatItemSize"] = $CatItemSize_id;

            // Guarda el nombre del campo si existe

            $colors_string = '';
            if (!empty($colors_id)) {
                $temp = CatColorsItem::find($colors_id);
                if ($temp !== null) {
                    $colors_string = $temp->getName();
                }
            }
            $CatItemUnitsPerPackage_string = '';

            if (!empty($CatItemUnitsPerPackage_id)) {
                $temp = CatItemUnitBusiness::find($CatItemUnitsPerPackage_id);
                if ($temp !== null) {
                    $CatItemUnitsPerPackage_string = $temp->getName();
                }
            }
            $CatItemMoldProperty_string = '';

            if (!empty($CatItemMoldProperty_id)) {
                $temp = CatItemMoldProperty::find($CatItemMoldProperty_id);
                if ($temp !== null) {
                    $CatItemMoldProperty_string = $temp->getName();
                }
            }
            $CatItemProductFamily_string = '';

            if (!empty($CatItemProductFamily_id)) {
                $temp = CatItemProductFamily::find($CatItemProductFamily_id);
                if ($temp !== null) {
                    $CatItemProductFamily_string = $temp->getName();
                }
            }
            $CatItemMoldCavity_string = '';

            if (!empty($CatItemMoldCavity_id)) {
                $temp = CatItemMoldCavity::find($CatItemMoldCavity_id);
                if ($temp !== null) {
                    $CatItemMoldCavity_string = $temp->getName();
                }
            }
            $CatItemPackageMeasurement_string = '';

            if (!empty($CatItemPackageMeasurement_id)) {
                $temp = CatItemPackageMeasurement::find($CatItemPackageMeasurement_id);
                if ($temp !== null) {
                    $CatItemPackageMeasurement_string = $temp->getName();
                }
            }
            $CatItemStatus_string = '';

            if (!empty($CatItemStatus_id)) {
                $temp = CatItemStatus::find($CatItemStatus_id);
                if ($temp !== null) {
                    $CatItemStatus_string = $temp->getName();
                }
            }
            $CatItemUnitBusiness_string = '';

            if (!empty($CatItemUnitBusiness_id)) {
                $temp = CatItemUnitBusiness::find($CatItemUnitBusiness_id);
                if ($temp !== null) {
                    $CatItemUnitBusiness_string = $temp->getName();
                }
            }

            $CatItemSize_string = '';

            if (!empty($CatItemSize_id)) {
                $temp = CatItemSize::find($CatItemSize_id);
                if ($temp !== null) {
                    $CatItemSize_string = $temp->getName();
                }
            }


            $array['item']['extra']["string"]["colors"] = $colors_string;
            $array['item']['extra']["string"]["CatItemUnitsPerPackage"] = $CatItemUnitsPerPackage_string;
            $array['item']['extra']["string"]["CatItemMoldProperty"] = $CatItemMoldProperty_string;
            $array['item']['extra']["string"]["CatItemProductFamily"] = $CatItemProductFamily_string;
            $array['item']['extra']["string"]["CatItemMoldCavity"] = $CatItemMoldCavity_string;
            $array['item']['extra']["string"]["CatItemPackageMeasurement"] = $CatItemPackageMeasurement_string;
            $array['item']['extra']["string"]["CatItemStatus"] = $CatItemStatus_string;
            $array['item']['extra']["string"]["CatItemUnitBusiness"] = $CatItemUnitBusiness_string;
            $array['item']['extra']["string"]["CatItemSize"] = $CatItemSize_string;
        }
    }

    /**
     *
     * Añade elementos extra al momento de generar el pdf
     *
     * @param $array
     */
    public function getExtraDataToPrint(&$array){

        $array['image_url'] = ($this->image !== 'imagen-no-disponible.jpg')
            ? asset('storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $this->image)
            : asset("/logo/{$this->image}");
        $array['image_url_medium']=($this->image_medium !== 'imagen-no-disponible.jpg')
            ? asset('storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $this->image_medium)
            : asset("/logo/{$this->image_medium}");
        $array['image_url_small'] =($this->image_small !== 'imagen-no-disponible.jpg')
            ? asset('storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $this->image_small)
            : asset("/logo/{$this->image_small}");

    }

    /**
     * Crea o actualiza el precio de inventario.
     *
     * @param int    $item_warehouse_id
     * @param int    $warehouse_id
     * @param string $price
     *
     * @return $this
     * @throws Exception
     */
    public function setItemWarehousePrice(
        $item_warehouse_id = 0,
        $warehouse_id = 0,
        $price = ''
    )
    {
        if ($price != '') {
            ItemWarehousePrice::updateOrCreate([
                'item_id' => $this->id,
                'warehouse_id' => $warehouse_id,
            ], [
                'price' => $price,
            ]);
        } else if ($item_warehouse_id != 0) {
                ItemWarehousePrice::findOrFail($item_warehouse_id)->delete();
        }
        return $this;
    }

    /**
     * Llamado estatico de setItemWarehousePrice
     *
     * @param int    $item_id
     * @param int    $item_warehouse_id
     * @param int    $warehouse_id
     * @param string $price
     *
     * @return bool
     * @throws Exception
     */
    public static function setStaticItemWarehousePrice(
        $item_id = 0,
        $item_warehouse_id = 0,
        $warehouse_id = 0,
        $price = ''
    )
    {
        if($item_id != 0){
            $item = self::find($item_id);
            if($item !== null){
                $item->setItemWarehousePrice(
                    $item_warehouse_id ,
                    $warehouse_id ,
                    $price
                );
                return true;
            }
        }
        return false;
    }

    /**
     * Deuelve codificacion 64 para el codigo de barras
     *
     * @param int   $height
     * @param int[] $colour
     *
     * @return string
     */
    public function getBarCode(int $height = 60, $colour = [0,0,0]){

       $code = $this->getIdentificacionCode();
        if(empty($code)) {
            return null;
        }
        $generator = new BarcodeGeneratorPNG();
        return  "data:image/png;base64,".base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128, 1, $height, $colour));
    }

    /**
     * Obtiene el codigo de barras o el codigo interno para devolverlo
     * @return string|null
     */
    public function getIdentificacionCode()
    {
        $code = $this->barcode;
        if(empty($code)){
            $code = $this->internal_id;
        }
        return $code;
    }


    /**
     * Obtiene una estructura estandar para generar los codigos de barra
     * Normalmente, el alto ($barCodeHeight) de codigo de barradas es 60 px.
     *
     * @param int $barCodeHeight
     *
     * @return array
     */
    public function getBarCodeData($barCodeHeight = 60 ){

        $currency = $this->currency_type;
        if(empty($currency )){
            $currency = new CurrencyType();
        }
        $data = [];
        $data['internal_id'] = $this->internal_id;
        $data['name'] = $this->getDescription();
        $data['model'] = $this->model;
        $data['code'] = $this->getIdentificacionCode();
        $data['short_name'] = substr($data['name'],0,30);
        $data['short_model'] = substr($data['model'],0,10);
        $data['bar_code'] = $this->getBarCode($barCodeHeight);
        $data['price'] = $currency->symbol." ".$this->withoutRounding();
        $data['second_name'] = $this->second_name;
        $data['short_second_name'] = substr($data['second_name'],0,4);
        $data['talla'] = $data['short_second_name'];
        return $data;
    }


    /**
     * Genera los numeros para el codigo de barras sin redondeo.
     *
     * @param int $total_decimals
     *
     * @return mixed|string
     */
    public  function withoutRounding( $total_decimals = 2) {
        $number = (string)$this->sale_unit_price;
        if($number === '') {
            $number = '0';
        }
        if(strpos($number, '.') === false) {
            $number .= '.';
        }
        $number_arr = explode('.', $number);

        $decimals = substr($number_arr[1], 0, $total_decimals);
        if($decimals === false) {
            $decimals = '0';
        }

        $return = '';
        if($total_decimals == 0) {
            $return = $number_arr[0];
        } else {
            if(strlen($decimals) < $total_decimals) {
                $decimals = str_pad($decimals, $total_decimals, '0', STR_PAD_RIGHT);
            }
            $return = $number_arr[0] . '.' . $decimals;
        }
        return $return;
    }

    public function getDataToPurchase(){

        $full_description = ($this->internal_id)?$this->internal_id.' - '.$this->description:$this->description;
        return [
            'id' => $this->id,
            'item_code'  => $this->item_code,
            'full_description' => $full_description,
            'description' => $this->description,
            'currency_type_id' => $this->currency_type_id,
            'currency_type_symbol' => $this->currency_type->symbol,
            'sale_unit_price' => $this->sale_unit_price,
            'purchase_unit_price' => $this->purchase_unit_price,
            'unit_type_id' => $this->unit_type_id,
            'sale_affectation_igv_type_id' => $this->sale_affectation_igv_type_id,
            'purchase_affectation_igv_type_id' => $this->purchase_affectation_igv_type_id,
            'purchase_has_igv' => (bool) $this->purchase_has_igv,
            'has_perception' => (bool) $this->has_perception,
            'lots_enabled' => (bool) $this->lots_enabled,
            'percentage_perception' => $this->percentage_perception,
            'item_unit_types' => collect($this->item_unit_types)->transform(function($row) {
                // validar este
                /*
                dd([
                    __LINE__,
                    __FILE__,
                    $this->item_unit_types
                ]);
                */
                return [
                    'id' => $row->id,
                    'description' => "{$row->description}",
                    'item_id' => $row->item_id,
                    'unit_type_id' => $row->unit_type_id,
                    'quantity_unit' => $row->quantity_unit,
                    'price1' => $row->price1,
                    'price2' => $row->price2,
                    'price3' => $row->price3,
                    'price_default' => $row->price_default,
                ];
            }),
            'series_enabled' => (bool) $this->series_enabled,

            // 'warehouses' => collect($row->warehouses)->transform(function($row) {
            //     return [
            //         'warehouse_id' => $row->warehouse->id,
            //         'warehouse_description' => $row->warehouse->description,
            //         'stock' => $row->stock,
            //     ];
            // })
        ];
    }


    /**
     * @param Builder                               $query
     * @param                                       $params
     *
     * @return Builder
     */
    public function scopeWhereFilterValuedKardexFormatSunat(Builder $query, $params)
    {

        if ($params->establishment_id) {

            return $query->with([
                    'document_items' => function ($q) use ($params) {
                        $q->whereHas('document', function ($q) use ($params) {
                            $q->whereValuedKardexFormatSunat($params)
                                ->where('establishment_id', $params->establishment_id);
                        });
                    },
                    'purchase_item' => function ($q) use ($params) {
                        $q->whereHas('purchase', function ($q) use ($params) {
                            $q->whereValuedKardexFormatSunat($params)
                                ->where('establishment_id', $params->establishment_id);
                        });
                    },
                    'dispatch_items' => function ($q) use ($params) {
                        $q->whereHas('dispatch', function ($q) use ($params) {
                            $q->whereValuedKardexFormatSunat($params)
                                ->where('establishment_id', $params->establishment_id);
                        });
                    }
                ])
                ->without([
                    'currency_type', 'item_type', 'warehouses', 'item_unit_types', 'tags'
                ]);
        }


        return $query->with([
                'document_items' => function ($q) use ($params) {
                    $q->whereHas('document', function ($q) use ($params) {
                        $q->whereValuedKardexFormatSunat($params);
                    });
                },
                'purchase_item' => function ($q) use ($params) {
                    $q->whereHas('purchase', function ($q) use ($params) {
                        $q->whereValuedKardexFormatSunat($params);
                    });
                },
                'dispatch_items' => function ($q) use ($params) {
                    $q->whereHas('dispatch', function ($q) use ($params) {
                        $q->whereValuedKardexFormatSunat($params);
                    });
                }
            ])
            ->without([
                'currency_type', 'item_type', 'warehouses', 'item_unit_types', 'tags'
            ]);
    }


    /**
     * Buscar codigo de la tabla 6
     */
    public function findUnitTypeCodeTableSix()
    {

        $record = $this->findUnitTypeCodeTableSixByDescription( strtoupper($this->unit_type->description) );
        if($record) return $record;

        //buscar otros
        $others = $this->findUnitTypeCodeTableSixByDescription('OTROS');
        $others['description'] = $others['description'] .' - '. strtoupper($this->unit_type->description);

        return $others;
    }


    /**
     * Buscar codigo de la tabla 6 por descripcion de unidad
     */
    public function findUnitTypeCodeTableSixByDescription($description)
    {

        return $this->getDataTableSixKardexSunat()->first(function($row) use($description){
            return $row['description'] == strtoupper($description);
        });

    }

    /**
     * Datos de la tabla 6 para reporte kardex valorizado formato sunat 13.1
     */
    public function getDataTableSixKardexSunat()
    {
        return collect([
            ['code' => '01', 'description' => 'KILOGRAMOS'],
            ['code' => '02', 'description' => 'LIBRAS'],
            ['code' => '03', 'description' => 'TONELADAS LARGAS'],
            ['code' => '04', 'description' => 'TONELADAS MÉTRICAS'],
            ['code' => '05', 'description' => 'TONELADAS CORTAS'],
            ['code' => '06', 'description' => 'GRAMOS'],
            ['code' => '07', 'description' => 'UNIDADES'],
            ['code' => '08', 'description' => 'LITROS'],
            ['code' => '09', 'description' => 'GALONES'],
            ['code' => '10', 'description' => 'BARRILES'],
            ['code' => '11', 'description' => 'LATAS'],
            ['code' => '12', 'description' => 'CAJAS'],
            ['code' => '13', 'description' => 'MILLARES'],
            ['code' => '14', 'description' => 'METROS CÚBICOS'],
            ['code' => '15', 'description' => 'METROS'],
            ['code' => '99', 'description' => 'OTROS'],
        ]);
    }


    /**
     * @return HasMany
     */
    public  function technical_service_item()
    {
        return $this->hasMany(TechnicalServiceItem::class, 'item_id');
    }

    /**
     * Devuelve el total de stock basado en la tabla item_movement
     *
     * Si $establisnment_id es diferente a 0, se sumaria basado en el establecimiento. Solo se suma los elementos countable
     *
     * @param int $establisnment_id
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getNewStock($establisnment_id = 0)
    {
        $query = \DB::connection('tenant')
            ->table('item_movement')
            ->where('countable', 1)
            ->where('item_id', $this->id);
        if ($establisnment_id != 0) {
            $query->where('establishment_id', $establisnment_id);
        }
        // Validacion para almacen?
        $query = $query->select(\DB::raw(' sum(quantity) as total'))->first();

        return $query->total;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function item_movement_rel_extra()
    {
        return $this->hasMany(ItemMovementRelExtra::class);
    }

    /**
     * Devuelve una estructura comun para los datos extra
     *
     * @return array
     */
    public function getExtraDataFields()
    {
        $data = [
                'colors' => ItemColor::ByItem($this->id)->get(),
                'CatItemUnitsPerPackage' => ItemUnitsPerPackage::ByItem($this->id)->get(),
                'CatItemMoldProperty' => ItemMoldProperty::ByItem($this->id)->get(),
                'CatItemUnitBusiness' => ItemUnitBusiness::ByItem($this->id)->get(),
                'CatItemStatus' => ItemStatus::ByItem($this->id)->get(),
                'CatItemPackageMeasurement' => ItemPackageMeasurement::ByItem($this->id)->get(),
                'CatItemMoldCavity' => ItemMoldCavity::ByItem($this->id)->get(),
                'CatItemProductFamily' => ItemProductFamily::ByItem($this->id)->get(),
                'CatItemSize' => ItemSize::ByItem($this->id)->get(),

        ];
        return $data;

    }

    /**
     * @return string|null
     */
    public function getUnitTypeText(){
        if(!empty($this->unit_type)) {
            return $this->unit_type->description;
        }

        return null;
    }

    public function scopeForProduction($query){
        return $query->where([
             ['item_type_id', '01'],
            ['unit_type_id', '!=', 'ZZ'],
             ['is_for_production', 1],
             ['is_set', 0]
        ]);

    }

    public function scopeForProductionSupply($query){
        return $query->where([
                ['item_type_id', '01'],
                ['unit_type_id', '!=', 'ZZ'],
                ['is_for_production', 0],
                ['is_set', 0]
        ]);

    }

    /**
     * Devuelve codigo interno - descripcion producto
     *
     * @return array
     */
    public function getInternalIdDescription()
    {
        return $this->internal_id ? "{$this->internal_id} - {$this->description}" : $this->description;
    }

    /**
     * @return HasMany
     */
    public function supplies()
    {
        return $this->hasMany(ItemSupply::class);
    }
    /**
     * @return HasMany
     */
    public function supplies_items()
    {
        return $this->hasMany(ItemSupply::class,'individual_item_id');
    }

    /**
     * @return bool
     */
    public function isIsForProduction(): bool
    {
        return (bool) $this->is_for_production;
    }

    /**
     * @param Builder $query
     *
     * @return mixed
     */
    public function scopeProductEnded(Builder $query){
        return $query->where([
            ['item_type_id', '01'],
            ['unit_type_id', '!=', 'ZZ'],
            ['is_for_production', 1]
        ])
            ->with('supplies')
            ->whereNotIsSet();
    }

    /**
     * @param Builder $query
     *
     * @return mixed
     */
    public function scopeProductSupply(Builder $query){
        $sup = ItemSupply::select('individual_item_id')->distinct()->pluck('individual_item_id');
        return $query->where([
            ['unit_type_id', '!=', 'ZZ'],
        ])
            ->wherein('id',$sup)
            ->with('supplies_items')
            ->whereNotIsSet()
            // ->join('items', 'item_supplies.individual_item_id', '=', 'items.id')
            ->distinct();
    }

    
    /**
     * Almacenes asociados al producto
     *
     * @return array
     */
    public function getDataWarehouses()
    {
        return collect($this->warehouses)->transform(function($row){
            return [
                'warehouse_description' => $row->warehouse->description,
                'stock' => $row->stock,
                'warehouse_id' => $row->warehouse_id,
            ];
        });
    }


    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeCategory($query, $id = null)
    {
        if($id){
            return $query->where('category_id', $id);
        }
    }

    public function scopeWhereStockMin($query)
    {
        $stockmin = (int)$this->stock_min;
        return $query->whereHas('warehouses', function($query) use($stockmin) {
            $query->where('stock', '<=', $stockmin);
        });
    }

    public function scopeWhereStockMinValidate($query)
    {
        $stockmin = (int)$this->stock_min;
        return $query->whereHas('warehouses', function($query) use($stockmin) {
            $query->where('stock', '>', $stockmin);
        });
    }
    

    /**
     * 
     * Obtener presentaciones
     *
     * Usado en: 
     * PosController
     * 
     * @param  bool $search_item_by_barcode_presentation
     * @param  string $barcode_presentation
     * @return array
     */
    public function getItemUnitTypesBarcode($search_item_by_barcode_presentation = false, $barcode_presentation)
    {
        return $search_item_by_barcode_presentation ? $this->item_unit_types()->where('barcode', $barcode_presentation)->get() : $this->item_unit_types;
    }

    /**
     * 
     * Filtrar por codigo de barra de presentacion
     * 
     * Usado en: 
     * PosController
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrFilterItemUnitTypeBarcode($query, $barcode)
    {
        return $query->orWhereHas('item_unit_types', function($query) use($barcode) {
            $query->where('barcode', $barcode);
        });
    }

    /**
     * 
     * Filtrar por codigo de barra de presentacion
     * 
     * Usado en: 
     * SearchItemController
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterItemUnitTypeBarcode($query, $barcode)
    {
        return $query->whereHas('item_unit_types', function($query) use($barcode) {
            $query->where('barcode', $barcode);
        });
    }


    /**
     * 
     * Filtro para no incluir relaciones en consulta
     *
     * @param Builder $query
     * @return Builder
     */  
    public function scopeWhereFilterWithOutRelations($query)
    {
        return $query->withOut(['item_type', 'unit_type', 'currency_type', 'warehouses','item_unit_types', 'tags']);
    }


    /**
     * 
     * Filtro para consulta al actualizar precios
     * 
     * Usado en:
     * ItemUpdatePriceImport
     *
     * @param Builder $query
     * @param  string $internal_id
     * @return Builder
     */  
    public function scopeWhereFilterUpdatePrices($query, $internal_id)
    {
        return $query->whereFilterWithOutRelations()->where('internal_id', $internal_id)->select('id', 'internal_id', 'sale_unit_price', 'purchase_unit_price');
    }

    
    /**
     * 
     * Filtro avanzado para busqueda
     * Usado en:
     * ItemController - records
     * Modules\Inventory\Http\Controllers\ItemController - advancedItemsSearch
     * Modules\Inventory\Http\Controllers\InventoryController - records
     * 
     * @param Builder $query
     * @param  string $column
     * @param  string $value
     * @return Builder
     * 
     */  
    public function scopeWhereAdvancedRecordsSearch($query, $column, $value)
    {
        $search_values = $this->getSearchValues($value);

        return $query->where(function($q) use($search_values, $column){

            foreach ($search_values as $search_value) 
            {
                $q->where($column, 'like', "%{$search_value}%");
            }

        });
    }

    
    /**
     * 
     * Filtro para busqueda avanzada de items en reporte kardex
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeWhereFilterReportKardex($query)
    {
        return $query->whereNotIsSet()->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
    }
    

    /**
     * 
     * Datos del item para busqueda avanzada
     * 
     * Usado en:
     * Modules\Inventory\Http\Controllers\ItemController
     * 
     * @return array
     */
    public function getRowResourceAdvancedSearch()
    {

        $full_description = $this->getFullDescriptionAdvancedSearch();

        return [
            'id' => $this->id,
            'full_description' => $full_description,
            'internal_id' => $this->internal_id,
            'description' => $this->description,
        ];
    }

    
    /**
     * 
     * Descripcion del item para busqueda avanzada
     *
     * @return string
     */
    public function getFullDescriptionAdvancedSearch()
    {
        $description = ($this->internal_id) ? $this->internal_id . ' - ' . $this->description : $this->description;

        $category = "";
        if($this->category) $category = ($this->category->id) ? " - {$this->category->name}" : "";

        $brand = "";
        if($this->brand) $brand = ($this->brand->id) ? " - {$this->brand->name}" : "";

        return "{$description}{$category}{$brand}";
    }


}

