<?php


    namespace Modules\Production\Models;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class MachineType
     *
     * @property int                  $id
     * @property string|null               $name
     * @property string|null              $description
     * @property bool                 $active
     * @property Carbon|null          $created_at
     * @property Carbon|null          $updated_at
     * @property Collection|Machine[] $machines
     * @package Modules\Production\Models
     * @mixin ModelTenant
     * @property-read int|null        $machines_count
     * @method static Builder|MachineType newModelQuery()
     * @method static Builder|MachineType newQuery()
     * @method static Builder|MachineType query()
     */
    class MachineType extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'active' => 'bool'
        ];

        protected $fillable = [
            'name',
            'description',
            'active'
        ];

        public function machines()
        {
            return $this->hasMany(Machine::class);
        }
    }

