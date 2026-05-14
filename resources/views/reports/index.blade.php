@extends('layouts.app', ['title' => 'Search reports'])

@section('content')
<section class="mb-8">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <h1 class="text-3xl font-bold">Search approved reports</h1>
            <p class="mt-2 max-w-2xl text-stone-600">Browse verified lost and found items. Sign in to submit a report or message a reporter.</p>
        </div>
        @auth
            <a class="inline-flex h-11 items-center justify-center rounded-md bg-teal-700 px-5 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" href="{{ route('reports.create') }}">Submit report</a>
        @endauth
    </div>

    <form method="GET" action="{{ route('reports.index') }}" class="mt-6 grid gap-4 rounded-lg border border-stone-200 bg-white p-4 shadow-sm md:grid-cols-3 lg:grid-cols-6">
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium" for="q">Keyword</label>
            <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="q" name="q" value="{{ $filters['q'] ?? '' }}">
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
            <label class="block text-sm font-medium" for="category">Category</label>
            <select class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="category" name="category">
                <option value="">Any</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium" for="location">Location</label>
            <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="location" name="location" value="{{ $filters['location'] ?? '' }}">
        </div>
        <div class="flex items-end gap-2">
            <button class="h-11 flex-1 rounded-md bg-stone-950 px-4 font-semibold text-white hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-950 focus:ring-offset-2" type="submit">Search</button>
            <a class="inline-flex h-11 items-center rounded-md border border-stone-300 px-4 font-semibold text-stone-800 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('reports.index') }}">Reset</a>
        </div>
    </form>
</section>

<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @forelse ($reports as $report)
        <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase text-teal-800">{{ ucfirst($report->type) }} · {{ $report->category }}</p>
                    <h2 class="mt-1 text-xl font-semibold">{{ $report->title }}</h2>
                </div>
                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-900">Approved</span>
            </div>
            <p class="mt-3 line-clamp-3 text-sm text-stone-600">{{ $report->description }}</p>
            <dl class="mt-4 grid gap-2 text-sm text-stone-700">
                <div><dt class="font-medium">Location</dt><dd>{{ $report->location }}</dd></div>
                <div><dt class="font-medium">Date</dt><dd>{{ $report->item_date->format('M j, Y') }}</dd></div>
            </dl>
            <a class="mt-5 inline-flex h-10 items-center rounded-md border border-stone-300 px-4 text-sm font-semibold hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('reports.show', $report) }}">View details</a>
        </article>
    @empty
        <div class="rounded-lg border border-dashed border-stone-300 bg-white p-8 text-center md:col-span-2 xl:col-span-3">
            <h2 class="text-lg font-semibold">No approved reports found</h2>
            <p class="mt-2 text-stone-600">Adjust your filters or check back after administrators approve new reports.</p>
        </div>
    @endforelse
</section>

<div class="mt-6">{{ $reports->links() }}</div>
@endsection
