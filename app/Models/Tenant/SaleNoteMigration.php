<?php


namespace App\Models\Tenant;


use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;

/**
 * Class SaleNoteMigration
 *
 * @property int $id
 * @property int $sale_notes_id
 * @property int $user_id
 * @property int $success
 * @property string $url
 * @property int $remote_id
 * @property string|null $number
 * @property string|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SaleNoteMigration extends ModelTenant
{
    use UsesTenantConnection;
    protected $table = 'sale_note_migration';
    protected $perPage = 25;

    protected $casts = [
        'sale_notes_id' => 'int',
        'user_id' => 'int',
        'success' => 'int',
        'remote_id' => 'int'
    ];

    protected $fillable = [
        'sale_notes_id',
        'user_id',
        'success',
        'url',
        'remote_id',
        'number',
        'data',
    ];

    /**
     * @return int
     */
    public function getSaleNotesId(): int
    {
        return $this->sale_notes_id;
    }

    /**
     * @param int $sale_notes_id
     * @return SaleNoteMigration
     */
    public function setSaleNotesId(int $sale_notes_id): SaleNoteMigration
    {
        $this->sale_notes_id = $sale_notes_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return SaleNoteMigration
     */
    public function setUserId(int $user_id): SaleNoteMigration
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSuccess(): bool
    {
        return (bool)$this->success;
    }

    /**
     * @param bool $success
     * @return SaleNoteMigration
     */
    public function setSuccess(bool $success= false): SaleNoteMigration
    {
        $this->success = (bool)$success;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return SaleNoteMigration
     */
    public function setUrl(string $url): SaleNoteMigration
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return int
     */
    public function getRemoteId(): int
    {
        return $this->remote_id;
    }

    /**
     * @param int $remote_id
     * @return SaleNoteMigration
     */
    public function setRemoteId(int $remote_id): SaleNoteMigration
    {
        $this->remote_id = $remote_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @param string|null $number
     * @return SaleNoteMigration
     */
    public function setNumber(?string $number): SaleNoteMigration
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     * @return SaleNoteMigration
     */
    public function setData(?string $data): SaleNoteMigration
    {
        $this->data = $data;
        return $this;
    }

}
