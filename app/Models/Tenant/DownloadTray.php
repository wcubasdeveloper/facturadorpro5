<?php

namespace App\Models\Tenant;


use Carbon\Carbon;

/**
 * Class DownloadTray
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $module
 * @property string $format
 * @property string|null $path
 * @property string|null $file_name
 * @property string $status
 * @property Carbon|null $date_init
 * @property Carbon|null $date_end
 * @property string|null $payload_request
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User|null $user
 * @package App\Models\Tenant
 */
class DownloadTray extends ModelTenant
{
    protected $table = 'download_tray';

    protected $fillable = [
        'user_id',
        'module',
        'format',
        'file_name',
        'status',
        'date_init',
        'date_end',
        'payload_request',
        'path',
        'type'
    ];

    protected $casts = [
        'user_id' => 'int'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
