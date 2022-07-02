<?php

    namespace Modules\BusinessTurn\Models;

    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * Class DocumentHotel
     *
     * @property int                  $id
     * @property int                  $document_id
     * @property string               $number
     * @property string               $name
     * @property string               $identity_document_type_id
     * @property string               $sex
     * @property int                  $age
     * @property string               $civil_status
     * @property string               $nacionality
     * @property string               $origin
     * @property int                  $room_number
     * @property Carbon               $date_entry
     * @property Carbon               $time_entry
     * @property Carbon               $date_exit
     * @property Carbon               $time_exit
     * @property string|null          $ocupation
     * @property string|null          $room_type
     * @property string|null          $guests
     * @property Carbon|null          $created_at
     * @property Carbon|null          $updated_at
     * @property Document             $document
     * @property IdentityDocumentType $identity_document_type
     * @mixin ModelTenant
     * @package Modules\BusinessTurn\Models
     * @method static Builder|DocumentHotel newModelQuery()
     * @method static Builder|DocumentHotel newQuery()
     * @method static Builder|DocumentHotel query()
     * @method static Builder|DocumentHotel searchByDate($date_start = null, $date_end = null)
     */
    class DocumentHotel extends ModelTenant
    {
        use UsesTenantConnection;

        protected $fillable = [
            'document_id',
            'number',
            'name',
            'identity_document_type_id',
            'sex',
            'age',
            'civil_status',
            'nacionality',
            'origin',
            'room_number',
            'date_entry',
            'time_entry',
            'date_exit',
            'time_exit',
            'ocupation',
            'room_type',
            'guests',
        ];
        protected $casts = [
            'document_id' => 'int',
            'age' => 'int',
            'room_number' => 'int'
        ];

        /**
         * @return BelongsTo
         */
        public function document()
        {
            return $this->belongsTo(Document::class);
        }

        /**
         * @return BelongsTo
         */
        public function identity_document_type()
        {
            return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
        }

        /**
         * @param Builder $query
         * @param null    $date_start
         * @param null    $date_end
         *
         * @return Builder
         */
        public function scopeSearchByDate(Builder $query, $date_start = null, $date_end = null)
        {

            if ($date_end !== null && $date_start !== null) {
                $query->where([['date_entry', '>=', $date_start], ['date_exit', '<=', $date_end]]);
            }
            return $query;
        }
    }
