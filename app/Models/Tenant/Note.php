<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;

/**
 * App\Models\Tenant\Note
 *
 * @property-read \App\Models\Tenant\Document $affected_document
 * @property-read \App\Models\Tenant\Document $document
 * @property mixed $data_affected_document
 * @property-read NoteCreditType $note_credit_type
 * @property-read NoteDebitType $note_debit_type
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @mixin \Eloquent
 */
class Note extends ModelTenant
{
    protected $with = [
        'affected_document',
        'note_credit_type',
        'note_debit_type'
    ];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'note_type',
        'note_credit_type_id',
        'note_debit_type_id',
        'note_description',
        'affected_document_id',
        'data_affected_document',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document()
    {
        // return $this->hasOne(Document::class);
        return $this->belongsTo(Document::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affected_document()
    {
        return $this->belongsTo(Document::class, 'affected_document_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function note_credit_type()
    {
        return $this->belongsTo(NoteCreditType::class, 'note_credit_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function note_debit_type()
    {
        return $this->belongsTo(NoteDebitType::class, 'note_debit_type_id');
    }

    public function getDataAffectedDocumentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDataAffectedDocumentAttribute($value)
    {
        $this->attributes['data_affected_document'] = (is_null($value))?null:json_encode($value);
    }

    /**
     * @return bool
     */
    public function isDebit(){
        if($this->note_type === 'debit') return true;
        return false;
    }

    /**
     * @return \App\Models\Tenant\Document|\App\Models\Tenant\Document[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function getDocument(){
        return Document::find($this->document_id);
    }

}
