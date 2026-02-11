@extends('layouts.app')

@section('content')
<div class="py-12 bg-black min-h-screen text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold tracking-tight">Settings</h1>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-white">&larr; Back to Dashboard</a>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-2xl p-8">
            <h2 class="text-xl font-semibold mb-6">Vercel Configuration</h2>
            
            <form action="{{ route('settings.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Project ID</label>
                    <input type="text" name="project_id" value="{{ $config->project_id ?? '' }}" class="w-full bg-black border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="prj_..." required>
                    <p class="mt-1 text-xs text-gray-500">Found in Vercel Project Settings -> General</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">API Token</label>
                    <input type="password" name="api_token" class="w-full bg-black border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="vcp_..." required>
                    <p class="mt-1 text-xs text-gray-500">Create a Project Scoped Token in Vercel Account Settings</p>
                </div>

                <div class="pt-4 border-t border-white/10 flex justify-end">
                    <button type="submit" class="bg-white text-black font-bold px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>

        @if($config)
        <div class="mt-8 bg-red-900/10 border border-red-500/20 rounded-2xl p-8">
            <h2 class="text-xl font-semibold mb-6 text-red-500">Danger Zone</h2>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium text-white">Disconnect Vercel Project</h3>
                    <p class="text-sm text-gray-400 mt-1">This will remove your API token and stop syncing.</p>
                </div>
                <form action="{{ route('settings.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500/10 text-red-500 border border-red-500/50 hover:bg-red-500 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Disconnect
                    </button>
                </form>
            </div>
        </div>
        @endif
        </div>

        <div class="mt-8 bg-blue-900/10 border border-blue-500/20 rounded-xl p-6">
            <h3 class="text-blue-400 font-semibold mb-2">Security Note</h3>
            <p class="text-sm text-blue-200/60">
                Your API Token is encrypted using Laravel's <code>Crypt::encryptString()</code> before being stored in the database. 
                It never leaves this server unencrypted.
            </p>
        </div>

    </div>
</div>
@endsection
