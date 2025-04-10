<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\User;

class NotificationComposer
{
    public function compose(View $view)
    {
        // Data Notif
        $dataNotif = User::whereIn('role', ['user', 'petugas'])
            ->whereNull('email_verified_at')
            ->latest()
            ->get();

        $jumlahNotif = $dataNotif->count();

        $view->with(compact('dataNotif', 'jumlahNotif'));
    }
}
