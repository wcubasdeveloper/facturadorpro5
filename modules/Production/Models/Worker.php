<?php

    namespace Modules\Production\Models;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Builder;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
 

    class Worker extends ModelTenant
    {

        protected $fillable = [
            'identity_document_type_id',
            'number',
            'name',
            'birth_date',
            'admission_date',
            'occupation',
            'address',
            'email',
            'telephone',
        ];


        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function identity_document_type()
        {
            return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
        }


        public function getRowResource()
        {
            return [
                'id' => $this->id,
                'document_type' => $this->identity_document_type->description,
                'identity_document_type_id' => $this->identity_document_type_id,
                'number' => $this->number,
                'name' => $this->name,
                'birth_date' => $this->birth_date,
                'admission_date' => $this->admission_date,
                'occupation' => $this->occupation,
                'address' => $this->address,
                'email' => $this->email,
                'telephone' => $this->telephone,
            ];
        }
    }

