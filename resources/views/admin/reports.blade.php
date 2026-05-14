@extends('layouts.app', ['title' => 'Admin reports'])

@section('content')
<section class="mb-8">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <h1 class="text-3xl font-bold">Manage reports</h1>
            <p class="mt-2 text-stone-600">Review, verify, match, resolve, and export report records.</p>
        </div>
        <a class="inline-flex h-11 items-center rounded-md bg-stone-950 px-4 font-semibold text-white hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-950 focus:ring-offset-2" href="{{ route('admin.reports.export') }}">Export CSV</a>
    </div>

    <form method="GET" action="{{ route('admin.reports') }}" class="mt-6 grid gap-4 rounded-lg border border-stone-200 bg-white p-4 shadow-sm md:grid-cols-3 lg:grid-cols-6">
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium" for="q">Keyword</label>
            <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="q" name="q" value="{{ $filters['q'] ?? '' }}">
        </div>
        <div>
            <label class="block text-sm font-medium" for="status">Status</label>
            <select class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="status" name="status">
                <option value="">Any</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium" for="type">Type</label>
            <select class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="type" name="type">
                <option value="">Any</option>
                <option value="lost" @selected(($filters['type'] ?? '') === 'lost')>Lost</option>
                <option value="found" @selected(($filters['type'] ?? '') === 'found')>Found</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium" for="location">Location</label>
            <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="location" name="location" value="{{ $filters['location'] ?? '' }}">
        </div>
        <div class="flex items-end gap-2">
            <button class="h-11 flex-1 rounded-md bg-stone-950 px-4 font-semibold text-white hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-950 focus:ring-offset-2" type="submit">Filter</button>
            <a class="inline-flex h-11 items-center rounded-md border border-stone-300 px-4 font-semibold text-stone-800 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('admin.reports') }}">Reset</a>
        </div>
    </form>
</section>

<section class="space-y-4">
    @forelse ($reports as $report)
        <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <div class="grid gap-5 lg:grid-cols-[1fr_360px]">
                <div>
                    <p class="text-xs font-semibold uppercase text-teal-800">{{ ucfirst($report->type) }} · {{ $report->category }}</p>
                    <h2 class="mt-1 text-xl font-semibold">{{ $report->title }}</h2>
                    <p class="mt-2 text-sm text-stone-600">{{ $report->description }}</p>
                    <dl class="mt-4 grid gap-2 text-sm text-stone-700 sm:grid-cols-3">
                        <div><dt class="font-medium">Reporter</dt><dd>{{ $report->user?->name }}</dd></div>
                        <div><dt class="font-medium">Location</dt><dd>{{ $report->location }}</dd></div>
                        <div><dt class="font-medium">Date</dt><dd>{{ $report->item_date->format('M j, Y') }}</dd></div>
                    </dl>
                </div>
                <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-medium" for="status-{{ $report->id }}">Status</label>
                        <select class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="status-{{ $report->id }}" name="status">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($report->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium" for="admin_notes-{{ $report->id }}">Admin notes</label>
                        <textarea class="mt-1 min-h-24 w-full rounded-md border border-stone-300 px-3 py-2 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="admin_notes-{{ $report->id }}" name="admin_notes">{{ $report->admin_notes }}</textarea>
                    </div>
                    <button class="h-11 w-full rounded-md bg-teal-700 px-4 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" type="submit">Save status</button>
                </form>
            </div>
        </article>
    @empty
        <div class="rounded-lg border border-dashed border-stone-300 bg-white p-8 text-center text-stone-600">No reports match the filters.</div>
    @endforelse
</section>

<div class="mt-6">{{ $reports->links() }}</div>
@endsection
