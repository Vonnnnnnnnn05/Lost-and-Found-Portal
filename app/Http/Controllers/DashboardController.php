<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $reports = $request->user()
            ->itemReports()
            ->latest()
            ->paginate(10);

        return view('dashboard', [
            'reports' => $reports,
            'counts' => $request->user()
                ->itemReports()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }
}
