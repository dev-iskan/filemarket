<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class AccountStatsComposer {
    public function compose (View $view) {
        $user = auth()->user();

        $sales = $user->sales->count();
        $files = $user->files()->finished()->count();

        $view->with([
            'fileCount' => $files,
            'saleCount' => $sales,
            'lifetimeEarned' => $user->saleValueOverLifetime(),
            'thisMonthEarned' => $user->saleValueOverMonth()
        ]);
    }
}