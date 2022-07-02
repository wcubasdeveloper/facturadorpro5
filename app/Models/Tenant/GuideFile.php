<?php


    namespace App\Models\Tenant;


    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Support\Facades\Storage;
    use Modules\Order\Models\OrderNote;
    use Symfony\Component\HttpFoundation\StreamedResponse;

    /**
     * Guarda los archivos de guias correspondientes. Por los momentos es solo aplicable en compra, se deja
     * funcionabildiades para otros elementos Class GuideFile
     *
     * @property int         $id
     * @property string|null $filename
     * @property int|null    $purchase_id
     * @property int|null    $document_id
     * @property int|null    $order_note_id
     * @property int|null    $quotation_id
     * @property int|null    $sale_note_id
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @package App\Models
     */
    class GuideFile extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'purchase_id' => 'int',
            'document_id' => 'int',
            'order_note_id' => 'int',
            'quotation_id' => 'int',
            'sale_note_id' => 'int'
        ];

        protected $fillable = [
            'filename',
            'purchase_id',
            'document_id',
            'order_note_id',
            'quotation_id',
            'sale_note_id'
        ];

        /**
         * @return string|null
         */
        public function getFilename(): ?string
        {
            return $this->filename;
        }

        /**
         * @param string|null $filename
         *
         * @return GuideFile
         */
        public function setFilename(?string $filename): GuideFile
        {
            $this->filename = $filename;
            return $this;
        }

        /**
         * @return Int|null
         */
        public function getPurchaseId(): ?int
        {
            return $this->purchase_id;
        }

        /**
         * @param Int|null $purchase_id
         *
         * @return GuideFile
         */
        public function setPurchaseId(?int $purchase_id): GuideFile
        {
            $this->purchase_id = $purchase_id;
            return $this;
        }

        /**
         * @return Int|null
         */
        public function getDocumentId(): ?int
        {
            return $this->document_id;
        }

        /**
         * @param Int|null $document_id
         *
         * @return GuideFile
         */
        public function setDocumentId(?int $document_id): GuideFile
        {
            $this->document_id = $document_id;
            return $this;
        }

        /**
         * @return Int|null
         */
        public function getOrderNoteId(): ?int
        {
            return $this->order_note_id;
        }

        /**
         * @param Int|null $order_note_id
         *
         * @return GuideFile
         */
        public function setOrderNoteId(?int $order_note_id): GuideFile
        {
            $this->order_note_id = $order_note_id;
            return $this;
        }

        /**
         * @return Int|null
         */
        public function getQuotationId(): ?int
        {
            return $this->quotation_id;
        }

        /**
         * @param Int|null $quotation_id
         *
         * @return GuideFile
         */
        public function setQuotationId(?int $quotation_id): GuideFile
        {
            $this->quotation_id = $quotation_id;
            return $this;
        }

        /**
         * @return Int|null
         */
        public function getSaleNoteId(): ?int
        {
            return $this->sale_note_id;
        }

        /**
         * @param Int|null $sale_note_id
         *
         * @return GuideFile
         */
        public function setSaleNoteId(?int $sale_note_id): GuideFile
        {
            $this->sale_note_id = $sale_note_id;
            return $this;
        }

        /**
         * @return BelongsTo
         */
        public function purchases(): BelongsTo
        {
            return $this->belongsTo(Purchase::class, 'purchase_id');
        }

        /**
         * @return BelongsTo
         */
        public function documents(): BelongsTo
        {
            return $this->belongsTo(Document::class, 'document_id');
        }

        /**
         * @return BelongsTo
         */
        public function order_notes(): BelongsTo
        {
            return $this->belongsTo(OrderNote::class, 'order_note_id');
        }

        /**
         * @return BelongsTo
         */
        public function quotations(): BelongsTo
        {
            return $this->belongsTo(Quotation::class, 'quotation_id');
        }

        /**
         * @return BelongsTo
         */
        public function sale_notes(): BelongsTo
        {
            return $this->belongsTo(SaleNote::class, 'sale_note_id');
        }

        /**
         * Guarda el archivo basado en la direccion de almacenamiento temporal de php
         *
         * @param $temp_path
         */
        public function saveFiles($temp_path): ?string
        {
            $data = self::getTypeOperation($this);
            $this->checkFile($data);

            $type = $data['type'];
            $id = $data['id'];

            $file_content = file_get_contents($temp_path);

            Storage::disk('tenant')->put('guide_files' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $this->filename, $file_content);
            return $this->filename;

        }

        /**
         * Devuelve un array de tipos para su uso estandarizado.
         *
         * @param GuideFile $element
         *
         * @return array
         */
        protected static function getTypeOperation(self $element)
        {

            $type = 'unknown';
            $id = 0;

            if ($element->purchase_id != 0) {
                $type = 'purchase';
                $id = $element->purchase_id;
            } elseif ($element->document_id != 0) {
                $type = 'document';
                $id = $element->document_id;
            } elseif ($element->order_note_id != 0) {
                $type = 'order_note';
                $id = $element->order_note_id;
            } elseif ($element->quotation_id != 0) {
                $type = 'quotation';
                $id = $element->quotation_id;
            } elseif ($element->sale_note_id != 0) {
                $type = 'sale_note';
                $id = $element->sale_note_id;
            }
            return [
                'type' => $type,
                'id' => $id
            ];
        }

        public function checkFile($data = [])
        {
            Storage::disk('tenant');
            $base = 'guide_files';

            if (!Storage::exists($base)) {
                Storage::makeDirectory($base);
            }
            if (!Storage::exists($base . DIRECTORY_SEPARATOR . $data['type'])) {
                Storage::makeDirectory($base . DIRECTORY_SEPARATOR . $data['type']);
            }
            if (!Storage::exists($base . DIRECTORY_SEPARATOR . $data['id'])) {
                Storage::makeDirectory($base . DIRECTORY_SEPARATOR . $data['id']);
            }

        }

        /**
         * Obtiene el archivo para poder descargarlo adecuadamente
         *
         * @return StreamedResponse
         */
        public function download()
        {
            $data = self::getTypeOperation($this);
            $this->checkFile($data);

            return Storage::disk('tenant')->download('guide_files' . DIRECTORY_SEPARATOR . $data['type'] . DIRECTORY_SEPARATOR . $data['id'] . DIRECTORY_SEPARATOR . $this->filename);
        }


    }
