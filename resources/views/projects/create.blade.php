@extends('layouts.app')

@section('content')
    <div class="w-1/2 mx-auto">
        <div class="text-xl text-center font-bold mb-6">{{ __('Create a new project') }}</div>
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('projects.store') }}">
            @csrf
            @include('projects._from', ['button_text' => 'Create project'])
        </form>
    </div>
@endsection
