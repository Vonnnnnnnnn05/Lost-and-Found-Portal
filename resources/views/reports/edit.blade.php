@extends('layouts.app', ['title' => 'Edit report'])

@section('content')
<section>
    <h1 class="text-3xl font-bold">Edit report</h1>
    <p class="mt-2 max-w-2xl text-stone-600">Saving changes returns this report to pending review.</p>
    <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data" class="mt-6 rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        @include('reports._form', ['button' => 'Save changes', 'method' => 'PUT'])
    </form>
</section>
@endsection
