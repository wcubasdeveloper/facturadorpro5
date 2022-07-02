<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Modules\LevelAccess\Models\ModuleLevel;

/**
 * Class Module
 *
 * @package App\Models\Tenant
 * @mixin ModelTenant
 * @property int $id
 * @property string $value
 * @property string $description
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|ModuleLevel[] $levels
 * @property-read int|null $levels_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereValue($value)
 */
class Module extends ModelTenant
{
    use UsesTenantConnection;

    /**
     * @var string[]
     */
    protected $with = ['levels'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'value',
        'order_menu',
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function levels()
    {
        return $this->hasMany(ModuleLevel::class);
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
     * @return Module
     */
    public function setDescription(string $description)
    : Module {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    : string {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return Module
     */
    public function setValue(string $value)
    : Module {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderMenu()
    : int {
        return $this->order_menu;
    }

    /**
     * @param int $order_menu
     *
     * @return Module
     */
    public function setOrderMenu(int $order_menu)
    : Module {
        $this->order_menu = $order_menu;
        return $this;
    }

    /**
     * @return $this
     */
    public function setLastOrderMenuInt(){
        $this->setOrderMenu(self::where('id','!=',$this->id)->select('order_menu')->max('order_menu'));
        return $this;
    }
}
