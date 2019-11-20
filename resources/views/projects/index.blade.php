@extends('layouts.app')

@section('content')
    <header class="flex items-center py-3">
        <div class="flex justify-between items-end w-full">
            <h2 class="text-gray-600 text-normal text-sm">My projects</h2>
            <a href="{{ route('projects.create') }}" class="btn-blue">New project</a>
        </div>
    </header>

    <main class="flex flex-wrap -mx-3">
        @forelse($projects as $project)
            <div class="w-1/3 px-3 pb-6">
                @include('projects.card', $project)
            </div>
        @empty
            <div>No projects yet.</div>
        @endforelse
    </main>
@endsection
