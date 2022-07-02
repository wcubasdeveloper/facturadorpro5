<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;

/**
 * Class DocumentaryObservation
 *
 * @property int $id
 * @property int|null $doc_file_id
 * @property string|null $observation
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property DocumentaryFile|null $doc_file
 *
 *@package App\Models\Tenant\ModelTenant
     * @mixin  \Eloquent
 */
class DocumentaryObservation extends ModelTenant
{
    use UsesTenantConnection;
    protected $table = 'documentary_observation';
    protected $perPage = 25;

    protected $casts = [
        'doc_file_id' => 'int'
    ];

    protected $fillable = [
        'doc_file_id',
        'observation',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_file()
    {
        return $this->belongsTo(DocumentaryFile::class, 'doc_file_id');
    }

    public function getCollectionData(){
        $data = [];
        $data['observation']=$this->observation;
        $data['created_at']=$this->created_at->format('Y-m-d H:i');
        return $data;
    }

    /**
     * @return int|null
     */
    public function getDocFileId()
    : ?int {
        return $this->doc_file_id;
    }

    /**
     * @param int|null $doc_file_id
     *
     * @return DocumentaryObservation
     */
    public function setDocFileId(?int $doc_file_id)
    : DocumentaryObservation {
        $this->doc_file_id = $doc_file_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getObservation()
    : ?string {
        return $this->observation;
    }

    /**
     * @param string|null $observation
     *
     * @return DocumentaryObservation
     */
    public function setObservation(?string $observation)
    : DocumentaryObservation {
        if(!empty($observation)) {
            $this->observation = $observation;
        }else{
            $this->observation = 'Se ha hecho una observación';
        }
        return $this;
    }


}
