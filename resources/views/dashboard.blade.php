@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
<section class="mb-8 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
    <div>
        <h1 class="text-3xl font-bold">My reports</h1>
        <p class="mt-2 text-stone-600">Track review status and update your submitted lost or found reports.</p>
    </div>
    <a class="inline-flex h-11 items-center justify-center rounded-md bg-teal-700 px-5 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" href="{{ route('reports.create') }}">Submit report</a>
</section>

<section class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
    @foreach (['pending', 'approved', 'rejected', 'matched', 'resolved'] as $status)
        <div class="rounded-lg border border-stone-200 bg-white p-4 shadow-sm">
            <p class="text-sm font-medium capitalize text-stone-600">{{ $status }}</p>
            <p class="mt-2 text-3xl font-bold">{{ $counts[$status] ?? 0 }}</p>
        </div>
    @endforeach
</section>

<section class="overflow-hidden rounded-lg border border-stone-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-stone-200 text-left text-sm">
            <thead class="bg-stone-100 text-xs uppercase text-stone-600">
                <tr>
                    <th class="px-4 py-3 font-semibold">Report</th>
                    <th class="px-4 py-3 font-semibold">Type</th>
                    <th class="px-4 py-3 font-semibold">Location</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Submitted</th>
                    <th class="px-4 py-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse ($reports as $report)
                    <tr>
                        <td class="px-4 py-4">
                            <p class="font-semibold">{{ $report->title }}</p>
                            <p class="text-stone-600">{{ $report->category }}</p>
                        </td>
                        <td class="px-4 py-4 capitalize">{{ $report->type }}</td>
                        <td class="px-4 py-4">{{ $report->location }}</td>
                        <td class="px-4 py-4">
                            <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold capitalize text-stone-800">{{ $report->status }}</span>
                        </td>
                        <td class="px-4 py-4">{{ $report->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap gap-2">
                                @if ($report->status === 'approved')
                                    <a class="rounded-md border border-stone-300 px-3 py-2 font-medium hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('reports.show', $report) }}">View</a>
                                @endif
                                <a class="rounded-md border border-stone-300 px-3 py-2 font-medium hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('reports.edit', $report) }}">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-stone-600" colspan="6">No reports yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-stone-200 px-4 py-3">{{ $reports->links() }}</div>
</section>
@endsection
