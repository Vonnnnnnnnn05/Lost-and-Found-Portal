<?php

namespace App\Http\Controllers;

use App\Models\ItemReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.index', [
            'pending' => ItemReport::query()->where('status', 'pending')->latest()->limit(8)->get(),
            'counts' => ItemReport::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }

    public function reports(Request $request): View
    {
        $filters = $request->only(['q', 'type', 'category', 'location', 'status', 'date_from', 'date_to']);

        $reports = ItemReport::query()
            ->with('user')
            ->search($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports', [
            'reports' => $reports,
            'filters' => $filters,
            'statuses' => ItemReport::STATUSES,
        ]);
    }

    public function updateReport(Request $request, ItemReport $report): RedirectResponse
    {
        $attributes = $request->validate([
            'status' => ['required', Rule::in(ItemReport::STATUSES)],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $report->update($attributes);

        return back()->with('status', 'Report updated.');
    }

    public function export(): StreamedResponse
    {
        return Response::streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Type', 'Title', 'Category', 'Location', 'Item Date', 'Status', 'Reporter', 'Submitted']);

            ItemReport::query()->with('user')->orderBy('id')->chunk(100, function ($reports) use ($handle): void {
                foreach ($reports as $report) {
                    fputcsv($handle, [
                        $report->id,
                        $report->type,
                        $report->title,
                        $report->category,
                        $report->location,
                        $report->item_date?->format('Y-m-d'),
                        $report->status,
                        $report->user?->name,
                        $report->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 'lost-found-reports.csv', ['Content-Type' => 'text/csv']);
    }
}
