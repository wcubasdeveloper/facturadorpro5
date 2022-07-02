<?php

    namespace App\Models\Tenant\Catalogs;

    use App\Models\Tenant\Company;
    use App\Models\Tenant\Person;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Modules\BusinessTurn\Models\DocumentHotel;
    use Modules\BusinessTurn\Models\DocumentTransport;
    use Modules\Order\Models\Dispatcher;
    use Modules\Order\Models\Driver;

    /**
     * Class CatIdentityDocumentType
     *
     * @property string                         $id
     * @property bool                           $active
     * @property string                         $description
     * @property Collection|Company[]           $companies_where_identity_document_type
     * @property Collection|Dispatcher[]        $dispatchers_where_identity_document_type
     * @property Collection|DocumentHotel[]     $document_hotels_where_identity_document_type
     * @property Collection|DocumentTransport[] $document_transports_where_identity_document_type
     * @property Collection|Driver[]            $drivers_where_identity_document_type
     * @property Collection|Person[]            $people_where_identity_document_type
     * @package App\Models\Tenant\Catalog
     * @mixin ModelCatalog
     * @property-read int|null                  $companies_where_identity_document_type_count
     * @property-read int|null                  $dispatchers_where_identity_document_type_count
     * @property-read int|null                  $document_hotels_where_identity_document_type_count
     * @property-read int|null                  $document_transports_where_identity_document_type_count
     * @property-read int|null                  $drivers_where_identity_document_type_count
     * @property-read int|null                  $people_where_identity_document_type_count
     * @method static Builder|IdentityDocumentType newModelQuery()
     * @method static Builder|IdentityDocumentType newQuery()
     * @method static Builder|ModelCatalog orderByDescription()
     * @method static Builder|IdentityDocumentType query()
     * @method static Builder|ModelCatalog whereActive()
     */
    class IdentityDocumentType extends ModelCatalog
    {
        use UsesTenantConnection;

        public $incrementing = false;
        protected $table = "cat_identity_document_types";
        protected $casts = [
            'active' => 'bool'
        ];

        protected $fillable = [
            'id',
            'active',
            'description'
        ];

        /**
         * @return HasMany
         */
        public function companies_where_identity_document_type()
        {
            return $this->hasMany(Company::class, 'identity_document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function dispatchers_where_identity_document_type()
        {
            return $this->hasMany(Dispatcher::class, 'identity_document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function document_hotels_where_identity_document_type()
        {
            return $this->hasMany(DocumentHotel::class, 'identity_document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function document_transports_where_identity_document_type()
        {
            return $this->hasMany(DocumentTransport::class, 'identity_document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function drivers_where_identity_document_type()
        {
            return $this->hasMany(Driver::class, 'identity_document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function people_where_identity_document_type()
        {
            return $this->hasMany(Person::class, 'identity_document_type_id', 'id');
        }
    }
