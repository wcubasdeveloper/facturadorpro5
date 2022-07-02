<?php

    namespace Modules\DocumentaryProcedure\Models;

    use App\Models\Tenant\ModelTenant;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Class DocumentaryGuidesNumberStatus
     *
     * @property int    $id
     * @property string $name
     * @property string|null $color
     * @package Modules\DocumentaryProcedure\Models
     * @method static Builder|DocumentaryGuidesNumberStatus newModelQuery()
     * @method static Builder|DocumentaryGuidesNumberStatus newQuery()
     * @method static Builder|DocumentaryGuidesNumberStatus query()
     * @mixin Eloquent
     */
    class DocumentaryGuidesNumberStatus extends ModelTenant
    {
        use UsesTenantConnection;

        public $timestamps = false;
        protected $table = 'documentary_guides_number_status';
        protected $fillable = [
            'name',
            'color',
        ];
        public function getCollectionData()
        {
            $data = $this->toArray();
            return $data;
        }
    }
