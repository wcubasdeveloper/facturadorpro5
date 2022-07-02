<?php

namespace Modules\Report\Http\ViewComposers;
use App\Models\Tenant\DownloadTray;
use Carbon\Carbon;

class DownloadTryViewComposer
{
    public function compose($view)
    {
        $view->vc_finished_downloads = DownloadTray::whereDate('created_at', Carbon::today())->where('status', 'FINISHED')->count();
    }
}