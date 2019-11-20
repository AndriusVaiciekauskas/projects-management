@extends('layouts.app')

@section('content')
    <div class="card w-1/3 mx-auto">
        <div class="text-xl text-center font-bold mb-6">{{ __('Login') }}</div>

        <form class="w-full max-w-lg" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
                        Email
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                           type="text"
                           placeholder="Email"
                           name="email"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
                        Password
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                           type="password"
                           placeholder="************"
                           name="password"
                           value="{{ old('password') }}">
                    @error('password')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-blue w-full">Login</button>
        </form>
    </div>
@endsection
