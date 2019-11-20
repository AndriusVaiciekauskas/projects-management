@extends('layouts.app')

@section('content')
    <header class="flex items-center py-3">
        <div class="flex justify-between items-end w-full">
            <p class="text-gray-600 text-normal text-sm">
                <a href="{{ route('projects.index') }}" class="no-underline text-gray-600 text-normal text-sm">
                    My projects
                </a> / {{ $project->title }}
            </p>
            <a href="{{ route('projects.edit', $project->id) }}" class="btn-blue">Edit project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-600 text-normal text-sm mb-3">Tasks</h2>
                    @foreach($project->tasks as $task)

                        <form action="{{ route('project.tasks.update', [$project->id, $task->id]) }}" method="POST">
                            <div class="card mb-3">
                                @method('PATCH')
                                @csrf
                                <div class="flex">
                                    <input type="text" name="body" value="{{ $task->body }}" class="w-full {{ $task->completed ? 'text-gray-500' : '' }}">
                                    <input type="checkbox" name="completed" {{ $task->completed ? 'checked' : '' }} onChange="this.form.submit()">
                                </div>
                            </div>
                        </form>
                    @endforeach

                    <div class="card mb-3">
                        <form action="{{ route('project.tasks.store', $project->id) }}" method="POST">
                            @csrf
                            <input type="text" name="body" class="w-full" placeholder="Add task">
                        </form>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-lg text-gray-600 text-normal text-sm mb-3">General notes</h2>
                    <form action="{{ route('projects.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea class="card w-full" style="min-height: 200px;" name="notes">{{ $project->notes }}</textarea>

                        <input type="submit" class="btn-blue">
                    </form>
                </div>
            </div>

            <div class="lg:w-1/4 px-3">
                @include('projects.card', $project)
                @include('projects.activity.card')
            </div>
        </div>
    </main>
@endsection
