<?php

namespace Modules\Order\Models;

use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\ModelTenant;

/**
 * Class Driver
 *
 * @package Modules\Order\Models
 * @mixin ModelTenant
 */
class Driver extends ModelTenant
{

    protected $with = ['identity_document_type'];

    protected $fillable = [
        'identity_document_type_id',
        'number',
        'name',
        'license',
        'telephone',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

}
