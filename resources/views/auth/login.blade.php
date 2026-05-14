@extends('layouts.app', ['title' => 'Sign in'])

@section('content')
<section class="mx-auto max-w-md">
    <h1 class="text-3xl font-bold">Sign in</h1>
    <p class="mt-2 text-stone-600">Access your reports and protected messages.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5 rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label class="block text-sm font-medium" for="email">Email</label>
            <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
            @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium" for="password">Password</label>
            <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="password" name="password" type="password" autocomplete="current-password" required>
            @error('password') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <label class="flex items-center gap-2 text-sm text-stone-700">
            <input class="rounded border-stone-300 text-teal-700 focus:ring-teal-700" name="remember" type="checkbox" value="1">
            Remember me
        </label>
        <button class="h-11 w-full rounded-md bg-teal-700 px-4 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" type="submit">Sign in</button>
    </form>
</section>
@endsection
