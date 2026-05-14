@extends('layouts.app', ['title' => 'Messages'])

@section('content')
<section class="mb-8">
    <h1 class="text-3xl font-bold">Protected messages</h1>
    <p class="mt-2 max-w-2xl text-stone-600">Messages are linked to approved item reports and keep contact details private.</p>
</section>

<section class="space-y-4">
    @forelse ($messages as $message)
        <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                <div>
                    <p class="text-sm font-semibold text-teal-800">{{ $message->report?->title }}</p>
                    <h2 class="mt-1 text-lg font-semibold">
                        @if ($message->sender_id === auth()->id())
                            Sent to {{ $message->recipient?->name }}
                        @else
                            From {{ $message->sender?->name }}
                        @endif
                    </h2>
                </div>
                <time class="text-sm text-stone-500" datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->format('M j, Y g:i A') }}</time>
            </div>
            <p class="mt-4 whitespace-pre-line text-stone-700">{{ $message->body }}</p>
            @if ($message->report?->status === 'approved')
                <a class="mt-4 inline-flex h-10 items-center rounded-md border border-stone-300 px-4 text-sm font-semibold hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('reports.show', $message->report) }}">Open report</a>
            @endif
        </article>
    @empty
        <div class="rounded-lg border border-dashed border-stone-300 bg-white p-8 text-center">
            <h2 class="text-lg font-semibold">No messages yet</h2>
            <p class="mt-2 text-stone-600">When you contact a reporter or receive a message, it will appear here.</p>
        </div>
    @endforelse
</section>

<div class="mt-6">{{ $messages->links() }}</div>
@endsection
