<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\Establishment;
use App\Models\Tenant\ModelTenant;

/**
 * Modules\Inventory\Models\Warehouse
 *
 * @property-read Establishment $establishment
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Inventory\Models\InventoryKardex[] $inventory_kardex
 * @property-read int|null $inventory_kardex_count
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @mixin \Eloquent
 */
class Warehouse extends ModelTenant
{
    protected $fillable = [
        'establishment_id',
        'description',
    ];

    /**
     * Se usa en la relacion con el inventario kardex en modules/Inventory/Traits/InventoryTrait.php.
     * Tambien se debe tener en cuenta modules/Inventory/Providers/InventoryKardexServiceProvider.php y
     * app/Providers/KardexServiceProvider.php para la correcta gestion de kardex
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventory_kardex()
    {
        return $this->hasMany(InventoryKardex::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * @return int
     */
    public function getEstablishmentId()
    : int {
        return $this->establishment_id;
    }

    /**
     * @param int $establishment_id
     *
     * @return Warehouse
     */
    public function setEstablishmentId(int $establishment_id)
    : Warehouse {
        $this->establishment_id = $establishment_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    : string {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Warehouse
     */
    public function setDescription(string $description)
    : Warehouse {
        $this->description = $description;
        return $this;
    }

}
