<?php

namespace App\Http\Controllers;

use App\Models\ItemReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'type', 'category', 'location', 'status', 'date_from', 'date_to']);

        $reports = ItemReport::query()
            ->approved()
            ->search($filters)
            ->latest('item_date')
            ->paginate(12)
            ->withQueryString();

        return view('reports.index', [
            'reports' => $reports,
            'filters' => $filters,
            'categories' => $this->categories(),
        ]);
    }

    public function create(): View
    {
        return view('reports.create', [
            'report' => new ItemReport(['type' => 'lost', 'item_date' => now()]),
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $this->validated($request);
        $attributes['user_id'] = $request->user()->id;
        $attributes['status'] = 'pending';

        if ($request->hasFile('image')) {
            $attributes['image_path'] = $request->file('image')->store('item-reports', 'public');
        }

        ItemReport::query()->create($attributes);

        return redirect()->route('dashboard')->with('status', 'Report submitted for admin review.');
    }

    public function show(ItemReport $report): View
    {
        abort_unless($report->status === 'approved', 404);

        return view('reports.show', ['report' => $report]);
    }

    public function edit(Request $request, ItemReport $report): View
    {
        abort_unless($report->user_id === $request->user()->id, 403);

        return view('reports.edit', [
            'report' => $report,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, ItemReport $report): RedirectResponse
    {
        abort_unless($report->user_id === $request->user()->id, 403);

        $attributes = $this->validated($request);
        $attributes['status'] = 'pending';
        $attributes['admin_notes'] = null;

        if ($request->hasFile('image')) {
            if ($report->image_path) {
                Storage::disk('public')->delete($report->image_path);
            }

            $attributes['image_path'] = $request->file('image')->store('item-reports', 'public');
        }

        $report->update($attributes);

        return redirect()->route('dashboard')->with('status', 'Report updated and returned to admin review.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(ItemReport::TYPES)],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:5000'],
            'location' => ['required', 'string', 'max:255'],
            'item_date' => ['required', 'date', 'before_or_equal:today'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function categories(): array
    {
        return ['Bags', 'Clothing', 'Documents', 'Electronics', 'IDs', 'Keys', 'Money', 'Other'];
    }
}
