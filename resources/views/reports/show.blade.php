@extends('layouts.app', ['title' => $report->title])

@section('content')
<article class="grid gap-8 lg:grid-cols-[1fr_360px]">
    <section class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-semibold uppercase text-teal-800">{{ ucfirst($report->type) }} · {{ $report->category }}</p>
        <h1 class="mt-2 text-3xl font-bold">{{ $report->title }}</h1>
        <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="font-medium text-stone-900">Location</dt><dd class="mt-1 text-stone-600">{{ $report->location }}</dd></div>
            <div><dt class="font-medium text-stone-900">Date</dt><dd class="mt-1 text-stone-600">{{ $report->item_date->format('M j, Y') }}</dd></div>
            <div><dt class="font-medium text-stone-900">Submitted</dt><dd class="mt-1 text-stone-600">{{ $report->created_at->format('M j, Y') }}</dd></div>
            <div><dt class="font-medium text-stone-900">Status</dt><dd class="mt-1 text-stone-600">{{ ucfirst($report->status) }}</dd></div>
        </dl>
        <div class="mt-6 border-t border-stone-200 pt-6">
            <h2 class="text-lg font-semibold">Description</h2>
            <p class="mt-2 whitespace-pre-line text-stone-700">{{ $report->description }}</p>
        </div>
    </section>

    <aside class="space-y-6">
        @if ($report->imageUrl())
            <img class="aspect-[4/3] w-full rounded-lg border border-stone-200 object-cover" src="{{ $report->imageUrl() }}" alt="Image for {{ $report->title }}">
        @endif

        <section class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold">Contact reporter</h2>
            @auth
                @if (auth()->id() === $report->user_id)
                    <p class="mt-2 text-sm text-stone-600">This is your report. Messages from other users will appear in your inbox.</p>
                @else
                    <form method="POST" action="{{ route('messages.store', $report) }}" class="mt-4 space-y-3">
                        @csrf
                        <label class="block text-sm font-medium" for="body">Message</label>
                        <textarea class="min-h-32 w-full rounded-md border border-stone-300 px-3 py-2 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="body" name="body" required>{{ old('body') }}</textarea>
                        @error('body') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                        <button class="h-11 w-full rounded-md bg-teal-700 px-4 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" type="submit">Send protected message</button>
                    </form>
                @endif
            @else
                <p class="mt-2 text-sm text-stone-600">Sign in to send a protected message. Reporter email and phone details are not shown publicly.</p>
                <a class="mt-4 inline-flex h-11 items-center rounded-md bg-teal-700 px-4 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" href="{{ route('login') }}">Sign in</a>
            @endauth
        </section>
    </aside>
</article>
@endsection
