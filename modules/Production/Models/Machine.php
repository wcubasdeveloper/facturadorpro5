<?php

    namespace Modules\Production\Models;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Class Machine
     *
     * @property int         $id
     * @property int         $machine_type_id
     * @property string|null      $name
     * @property string|null      $brand
     * @property string|null      $model
     * @property string|null      $closing_force
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property MachineType $machine_type
     * @mixin ModelTenant
     * @package Modules\Production\Models
     * @method static Builder|Machine newModelQuery()
     * @method static Builder|Machine newQuery()
     * @method static Builder|Machine query()
     */
    class Machine extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'machine_type_id' => 'int'
        ];

        protected $fillable = [
            'name',
            'machine_type_id',
            'brand',
            'model',
            'closing_force'
        ];

        public function machine_type()
        {
            return $this->belongsTo(MachineType::class);
        }

        public function getCollectionData(){

            $data = $this->toArray();
            $data['machine_type'] = $this->machine_type;
            return $data;
        }

        public function productions()
        {
            return $this->hasMany(Production::class);
        }
    }

