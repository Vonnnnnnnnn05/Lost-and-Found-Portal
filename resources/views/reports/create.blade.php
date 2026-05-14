@extends('layouts.app', ['title' => 'New report'])

@section('content')
<section>
    <h1 class="text-3xl font-bold">Submit a report</h1>
    <p class="mt-2 max-w-2xl text-stone-600">Reports are reviewed by administrators before they appear in public search.</p>
    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="mt-6 rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        @include('reports._form', ['button' => 'Submit for review'])
    </form>
</section>
@endsection
