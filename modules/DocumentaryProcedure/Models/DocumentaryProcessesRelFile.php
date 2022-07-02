<?php

/**
 *
 */

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;

/**
 * Class DocumentaryProcessesRelFile
 *
 * @property int $id
 * @property int|null $doc_processes_id
 * @property int|null $doc_file_id
 * @property int|null $doc_office_id
 * @property string|null $stages
 * @property int $complete
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property DocumentaryFile|null $doc_file
 * @property DocumentaryOffice|null $doc_office
 * @property DocumentaryProcess|null $doc_processes
 *
 *@package App\Models\Tenant\ModelTenant
     * @mixin  \Eloquent
 */
class DocumentaryProcessesRelFile extends ModelTenant
{
    use UsesTenantConnection;
    protected $table = 'documentary_processes_rel_file';
    protected $perPage = 25;

    protected $casts = [
        'doc_processes_id' => 'int',
        'doc_file_id' => 'int',
        'doc_office_id' => 'int',
        'complete' => 'int'
    ];

    protected $fillable = [
        'doc_processes_id',
        'doc_file_id',
        'doc_office_id',
        'stages',
        'complete'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_file()
    {
        return $this->belongsTo(DocumentaryFile::class, 'doc_file_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_office()
    {
        return $this->belongsTo(DocumentaryOffice::class, 'doc_office_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_processes()
    {
        return $this->belongsTo(DocumentaryProcess::class, 'doc_processes_id');
    }
}
