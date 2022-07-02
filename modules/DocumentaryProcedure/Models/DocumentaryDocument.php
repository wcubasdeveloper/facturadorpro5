<?php

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;

/**
 * Modules\DocumentaryProcedure\Models\DocumentaryDocument
 *
 * @property-read mixed $active
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentaryDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentaryDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentaryDocument query()
 * @mixin \Eloquent
 */
class DocumentaryDocument extends ModelTenant
{
    use UsesTenantConnection;
    protected $table = 'documentary_documents';

	protected $fillable = [
	    'description',
        'active',
        'name'];

	public function getActiveAttribute($value)
	{
		return $value ? true : false;
	}

    /**
     * @return string
     */
    public function getDescription()
     {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return DocumentaryDocument
     */
    public function setDescription( $description)
    : DocumentaryDocument {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
     {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return DocumentaryDocument
     */
    public function setName( $name)
    : DocumentaryDocument {
        $this->name = $name;
        return $this;
    }

    public function getCollectionData() {
        // $data = $this->toArray();

        $data = [
            'id' => $this->id,
            'description'=> $this->getDescription(),
            'active' => (bool)$this->active,
            'name' => $this->getName()
        ];
        return $data
            ;
    }
    }
