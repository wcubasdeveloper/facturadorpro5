<?php

    namespace App\Models\Tenant\Catalogs;

    use App\Models\Tenant\Dispatch;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\Perception;
    use App\Models\Tenant\PerceptionDocument;
    use App\Models\Tenant\Purchase;
    use App\Models\Tenant\PurchaseSettlement;
    use App\Models\Tenant\Retention;
    use App\Models\Tenant\RetentionDocument;
    use App\Models\Tenant\SaleNote;
    use App\Models\Tenant\Series;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Modules\Document\Models\SeriesConfiguration;
    use Modules\Purchase\Models\FixedAssetPurchase;


    /**
     * Class DocumentType
     *
     * @property string                           $id
     * @property bool                             $active
     * @property string|null                      $short
     * @property string                           $description
     * @property Collection|Dispatch[]            $dispatches_where_document_type
     * @property Collection|Document[]            $documents_where_document_type
     * @property Collection|FixedAssetPurchase[]  $fixed_asset_purchases_where_document_type
     * @property Collection|PerceptionDocument[]  $perception_documents_where_document_type
     * @property Collection|Perception[]          $perceptions_where_document_type
     * @property Collection|PurchaseSettlement[]  $purchase_settlements_where_document_type
     * @property Collection|Purchase[]            $purchases_where_document_type
     * @property Collection|RetentionDocument[]   $retention_documents_where_document_type
     * @property Collection|Retention[]           $retentions_where_document_type
     * @property Collection|Series[]              $series_where_document_type
     * @property Collection|SeriesConfiguration[] $series_configurations_where_document_type
     * @mixin ModelCatalog
     * @package App\Models\Tenant\Catalogs
     * @property-read int|null                    $dispatches_where_document_type_count
     * @property-read int|null                    $documents_where_document_type_count
     * @property-read int|null                    $fixed_asset_purchases_where_document_type_count
     * @property-read int|null                    $perception_documents_where_document_type_count
     * @property-read int|null                    $perceptions_where_document_type_count
     * @property-read int|null                    $purchase_settlements_where_document_type_count
     * @property-read int|null                    $purchases_where_document_type_count
     * @property-read int|null                    $retention_documents_where_document_type_count
     * @property-read int|null                    $retentions_where_document_type_count
     * @property-read int|null                    $series_configurations_where_document_type_count
     * @property-read int|null                    $series_where_document_type_count
     * @method static Builder|DocumentType documentsActiveToPurchase()
     * @method static Builder|DocumentType newModelQuery()
     * @method static Builder|DocumentType newQuery()
     * @method static Builder|DocumentType onlyActive()
     * @method static Builder|DocumentType onlyAvaibleDocuments()
     * @method static Builder|ModelCatalog orderByDescription()
     * @method static Builder|DocumentType query()
     * @method static Builder|ModelCatalog whereActive()
     */
    class DocumentType extends ModelCatalog
    {
        use UsesTenantConnection;

        public $incrementing = false;
        protected $table = "cat_document_types";
        protected $fillable = [
            'active',
            'short',
            'description'

        ];


        /**
         * @return mixed
         */
        public function getActive()
        {
            return $this->active;
        }

        /**
         * @param mixed $active
         *
         * @return DocumentType
         */
        public function setActive($active)
        {
            $this->active = $active;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getShort()
        {
            return $this->short;
        }

        /**
         * @param mixed $short
         *
         * @return DocumentType
         */
        public function setShort($short)
        {
            $this->short = $short;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @param mixed $description
         *
         * @return DocumentType
         */
        public function setDescription($description)
        {
            $this->description = $description;
            return $this;
        }

        /**
         * @return Builder
         */
        public function scopeOnlyActive($query)
        {
            return $query->where('active', 1);
        }

        /**
         * @return Builder
         */
        public function scopeOnlyAvaibleDocuments($query)
        {
            return $query->OnlyActive()->wherein('id', ['01', '03', '07', '08', '09', '20', '40', '80', '04']);
        }

        /**
         * Devuelve los elementos activos para compras
         *
         * @return Builder
         */
        public function scopeDocumentsActiveToPurchase($query)
        {
            return $query->OnlyActive()->wherein('id', ['01', '02', '03', 'GU75', 'NE76', '14', '07', '08']);
        }


        /**
         * @return HasMany
         */
        public function dispatches_where_document_type()
        {
            return $this->hasMany(Dispatch::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function documents_where_document_type()
        {
            return $this->hasMany(Document::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function fixed_asset_purchases_where_document_type()
        {
            return $this->hasMany(FixedAssetPurchase::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function perception_documents_where_document_type()
        {
            return $this->hasMany(PerceptionDocument::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function perceptions_where_document_type()
        {
            return $this->hasMany(Perception::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function purchase_settlements_where_document_type()
        {
            return $this->hasMany(PurchaseSettlement::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function purchases_where_document_type()
        {
            return $this->hasMany(Purchase::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function retention_documents_where_document_type()
        {
            return $this->hasMany(RetentionDocument::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function retentions_where_document_type()
        {
            return $this->hasMany(Retention::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function series_where_document_type()
        {
            return $this->hasMany(Series::class, 'document_type_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function series_configurations_where_document_type()
        {
            return $this->hasMany(SeriesConfiguration::class, 'document_type_id', 'id');
        }

        /**
         * Devuelve el nombre de la clase correspondiente al documento
         *
         * @return string
         */
        public function getCurrentRelatiomClass(){
            //09	1		GUIA DE REMISIÓN REMITENTE
            //20	1		COMPROBANTE DE RETENCIÓN ELECTRÓNICA
            //31	1		Guía de remisión transportista
            //40	1		COMPROBANTE DE PERCEPCIÓN ELECTRÓNICA
            //71	0		Guia de remisión remitente complementaria
            //72	0		Guia de remisión transportista complementaria
            //GU75	1		GUÍA
            //NE76	1		NOTA DE ENTRADA
            //02	1		RECIBO POR HONORARIOS
            //14	1		SERVICIOS PÚBLICOS
            //04	1		LIQUIDACIÓN DE COMPRA

            //01	1	FT	FACTURA ELECTRÓNICA
            if($this->id == '01'){ return Document::class;}
            //03	1	BV	BOLETA DE VENTA ELECTRÓNICA
            elseif($this->id == '03'){ return Document::class;}
            //07	1	NC	NOTA DE CRÉDITO
            elseif($this->id == '07'){ return Document::class;}
            //08	1	ND	NOTA DE DÉBITO
            elseif($this->id == '08'){ return Document::class;}
            // elseif($this->id == '09'){ return Document::class;}
            // elseif($this->id == '20'){ return Document::class;}
            // elseif($this->id == '31'){ return Document::class;}
            // elseif($this->id == '40'){ return Document::class;}
            // elseif($this->id == '71'){ return Document::class;}
            // elseif($this->id == '72'){ return Document::class;}
            // elseif($this->id == 'GU75'){ return Document::class;}
            // elseif($this->id == 'NE76'){ return Document::class;}
            //80	1		NOTA DE VENTA
            elseif($this->id == '80'){ return SaleNote::class;}
            // elseif($this->id == '02'){ return Document::class;}
            // elseif($this->id == '14'){ return Document::class;}
            // elseif($this->id == '04'){ return Document::class;}
            else{ return Document::class;}


        }
    }
