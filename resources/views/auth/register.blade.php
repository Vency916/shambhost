@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-black py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-400">
                Or
                <a href="{{ route('login') }}" class="font-medium text-blue-500 hover:text-blue-400">
                    sign in to existing account
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="name" class="sr-only">Full Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required class="appearance-none rounded-none rounded-t-lg relative block w-full px-3 py-2 border border-white/10 bg-white/5 placeholder-gray-500 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Full Name">
                </div>
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none relative block w-full px-3 py-2 border border-white/10 bg-white/5 placeholder-gray-500 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Email address">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none relative block w-full px-3 py-2 border border-white/10 bg-white/5 placeholder-gray-500 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Password">
                </div>
                <div>
                    <label for="password-confirm" class="sr-only">Confirm Password</label>
                    <input id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password" required class="appearance-none rounded-none rounded-b-lg relative block w-full px-3 py-2 border border-white/10 bg-white/5 placeholder-gray-500 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                </div>
            </div>

            @if ($errors->any())
                <div class="text-red-500 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-black bg-white hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
