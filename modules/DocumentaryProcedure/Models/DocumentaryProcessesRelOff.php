<?php

/**
 *
 */

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;

/**
 * Class DocumentaryProcessesRelOff
 *
 * @property int $id
 * @property int|null $doc_processes_id
 * @property int|null $doc_offices_id
 * @property bool  $active
 * @property int|null $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property DocumentaryOffice|null $doc_offices
 * @property DocumentaryProcess|null $doc_processes
 *
 *@package App\Models\Tenant\ModelTenant
     * @mixin  \Eloquent
 */
class DocumentaryProcessesRelOff extends ModelTenant
{
    use UsesTenantConnection;
    protected $table = 'documentary_processes_rel_off';
    protected $perPage = 25;

    protected $casts = [
        'doc_processes_id' => 'int',
        'doc_offices_id' => 'int',
        'active' => 'bool',
        'order' => 'int'
    ];

    protected $fillable = [
        'doc_processes_id',
        'doc_offices_id',
        'active',
        'order',
    ];

    /**
     * Devuelve la relacion con etapas
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_offices()
    {
        return $this->belongsTo(DocumentaryOffice::class, 'doc_offices_id');
    }

    /**
     * Devuelve la relacion con los tramites
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_processes()
    {
        return $this->belongsTo(DocumentaryProcess::class, 'doc_processes_id');
    }

    /**
     * @return array
     */
    public function getCollectionData(){
        $data = [
            'process' => $this->doc_processes(),
            'offices' => $this->doc_offices(),
            'active' => $this->getActive(),
            'order' =>$this->getOrder(),
        ];
        return $data;
    }

    /**
     * @return int|null
     */
    public function getDocProcessesId()
    : ?int {
        return $this->doc_processes_id;
    }

    /**
     * @param int|null $doc_processes_id
     *
     * @return DocumentaryProcessesRelOff
     */
    public function setDocProcessesId(?int $doc_processes_id)
    : DocumentaryProcessesRelOff {
        $this->doc_processes_id = $doc_processes_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDocOfficesId()
    : ?int {
        return $this->doc_offices_id;
    }

    /**
     * @param int|null $doc_offices_id
     *
     * @return DocumentaryProcessesRelOff
     */
    public function setDocOfficesId(?int $doc_offices_id)
    : DocumentaryProcessesRelOff {
        $this->doc_offices_id = $doc_offices_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrder()
    : ?int {
        return $this->order;
    }

    /**
     * @param int|null $order
     *
     * @return DocumentaryProcessesRelOff
     */
    public function setOrder(?int $order)
    : DocumentaryProcessesRelOff {
        $this->order = $order;
        return $this;
    }

    /**
     * @return bool
     */
    public function getActive()
    : bool {
        return (bool) $this->active;
    }

    /**
     * @param bool $active
     *
     * @return DocumentaryProcessesRelOff
     */
    public function setActive(bool $active)
    : DocumentaryProcessesRelOff {
        $this->active = (bool) $active;
        return $this;
    }


}
