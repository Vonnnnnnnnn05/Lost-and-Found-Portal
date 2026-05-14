<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Lost and Found Portal' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-stone-50 font-sans text-stone-950 antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-stone-950 focus:px-4 focus:py-2 focus:text-white">Skip to content</a>

    <header class="border-b border-stone-200 bg-white">
        <nav class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8" aria-label="Main navigation">
            <a href="{{ route('reports.index') }}" class="text-lg font-bold text-stone-950">Lost and Found Portal</a>
            <div class="flex flex-wrap items-center gap-2 text-sm font-medium">
                <a class="rounded-md px-3 py-2 text-stone-700 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('reports.index') }}">Search</a>
                @auth
                    <a class="rounded-md px-3 py-2 text-stone-700 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="rounded-md px-3 py-2 text-stone-700 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('messages.index') }}">Messages</a>
                    @if (auth()->user()->is_admin)
                        <a class="rounded-md px-3 py-2 text-stone-700 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('admin.index') }}">Admin</a>
                    @endif
                    <a class="rounded-md bg-teal-700 px-3 py-2 text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" href="{{ route('reports.create') }}">New report</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-md px-3 py-2 text-stone-700 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" type="submit">Sign out</button>
                    </form>
                @else
                    <a class="rounded-md px-3 py-2 text-stone-700 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('login') }}">Sign in</a>
                    <a class="rounded-md bg-teal-700 px-3 py-2 text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" href="{{ route('register') }}">Create account</a>
                @endauth
            </div>
        </nav>
    </header>

    <main id="main" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-medium text-teal-900" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                <p class="font-semibold">Please correct the highlighted fields.</p>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
