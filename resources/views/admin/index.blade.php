@extends('layouts.app', ['title' => 'Admin'])

@section('content')
<section class="mb-8 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
    <div>
        <h1 class="text-3xl font-bold">Admin dashboard</h1>
        <p class="mt-2 text-stone-600">Verify submissions, maintain records, and manage report outcomes.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a class="inline-flex h-11 items-center rounded-md border border-stone-300 px-4 font-semibold hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('admin.reports') }}">All reports</a>
        <a class="inline-flex h-11 items-center rounded-md bg-stone-950 px-4 font-semibold text-white hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-950 focus:ring-offset-2" href="{{ route('admin.reports.export') }}">Export CSV</a>
    </div>
</section>

<section class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
    @foreach (['pending', 'approved', 'rejected', 'matched', 'resolved'] as $status)
        <div class="rounded-lg border border-stone-200 bg-white p-4 shadow-sm">
            <p class="text-sm font-medium capitalize text-stone-600">{{ $status }}</p>
            <p class="mt-2 text-3xl font-bold">{{ $counts[$status] ?? 0 }}</p>
        </div>
    @endforeach
</section>

<section class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold">Pending review</h2>
        <a class="text-sm font-semibold text-teal-800 underline-offset-4 hover:underline" href="{{ route('admin.reports', ['status' => 'pending']) }}">View all</a>
    </div>
    <div class="mt-5 space-y-4">
        @forelse ($pending as $report)
            <article class="rounded-md border border-stone-200 p-4">
                <div class="flex flex-col justify-between gap-3 lg:flex-row lg:items-start">
                    <div>
                        <p class="text-xs font-semibold uppercase text-teal-800">{{ ucfirst($report->type) }} · {{ $report->category }}</p>
                        <h3 class="mt-1 font-semibold">{{ $report->title }}</h3>
                        <p class="mt-1 text-sm text-stone-600">{{ $report->location }} · {{ $report->item_date->format('M j, Y') }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="flex flex-wrap gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="admin_notes" value="">
                        <button class="h-10 rounded-md bg-teal-700 px-4 text-sm font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700" name="status" value="approved" type="submit">Approve</button>
                        <button class="h-10 rounded-md border border-red-300 px-4 text-sm font-semibold text-red-800 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-700" name="status" value="rejected" type="submit">Reject</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="rounded-md border border-dashed border-stone-300 p-6 text-center text-stone-600">No pending reports.</p>
        @endforelse
    </div>
</section>
@endsection
