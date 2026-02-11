@extends('layouts.app')

@section('content')
<div class="py-12 bg-black min-h-screen text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0 mb-8 md:mb-12">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Control Plane</h1>
            <div class="flex flex-wrap gap-3 md:gap-4 w-full md:w-auto">
                <span class="px-3 py-1 bg-white/10 rounded-full text-xs md:text-sm font-mono border border-white/10 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $backendLatency < 100 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    Lat: {{ $backendLatency }}ms
                </span>
                <span class="px-3 py-1 bg-white/10 rounded-full text-xs md:text-sm font-mono border border-white/10 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Status: {{ $vercelStatus }}
                </span>
                <a href="{{ route('settings.index') }}" class="p-1.5 md:p-2 bg-white/10 rounded-full hover:bg-white/20 transition-colors flex items-center justify-center shrink-0" title="Settings">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </a>
            </div>
        </div>

        @if(session('error'))
        <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Deployments -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Recent Deployments</h2>
                        <form action="{{ route('dashboard.refresh') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs bg-white/10 hover:bg-white/20 px-3 py-1 rounded text-gray-300 transition-colors">
                                ↻ Refresh
                            </button>
                        </form>
                    </div>
                    
                    @if($deployments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-gray-500 text-sm border-b border-white/10">
                                        <th class="pb-3 font-medium">Branch</th>
                                        <th class="pb-3 font-medium">State</th>
                                        <th class="pb-3 font-medium">Preview</th>
                                        <th class="pb-3 font-medium">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach($deployments as $deployment)
                                    <tr id="deployment-{{ $deployment->vercel_deployment_id }}" class="border-b border-white/5 last:border-0">
                                        <td class="py-4">
                                            <div class="flex flex-col">
                                                <span class="font-mono text-gray-300">{{ $deployment->branch }}</span>
                                                <a href="{{ route('deployments.logs', $deployment->vercel_deployment_id) }}" class="text-xs text-gray-500 hover:text-white mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    View Logs
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-4 deployment-state">
                                            <span class="px-2 py-1 rounded text-xs font-semibold
                                                {{ $deployment->state == 'READY' ? 'bg-green-500/20 text-green-400' : '' }}
                                                {{ $deployment->state == 'ERROR' ? 'bg-red-500/20 text-red-400' : '' }}
                                                {{ $deployment->state == 'BUILDING' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                            ">
                                                {{ $deployment->state }}
                                            </span>
                                        </td>
                                        <td class="py-4 deployment-url">
                                            @if($deployment->url)
                                                <a href="https://{{ $deployment->url }}" target="_blank" class="inline-flex items-center gap-1 text-blue-400 hover:text-white transition-colors group">
                                                    <span>Visit Preview</span>
                                                    <svg class="w-3 h-3 transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            @else
                                                <span class="text-gray-600">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-gray-500 deployment-time">{{ $deployment->created_at_vercel ? $deployment->created_at_vercel->diffForHumans() : 'Just now' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-gray-500 text-center py-8">
                            No deployments found. configure the webhook to see live updates.
                        </div>
                    @endif
                </div>

                <!-- Code Example -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-4">Integration Details</h2>
                     <p class="text-gray-400 mb-4 text-sm">To enable full observability, add this webhook to your Vercel Project Settings:</p>
                     <div class="bg-black p-3 rounded-lg border border-white/10 font-mono text-xs text-gray-300 flex justify-between items-center">
                        <span>{{ config('app.url') }}/api/webhooks/vercel</span>
                        <button class="text-gray-500 hover:text-white">Copy</button>
                     </div>
                </div>
            </div>

            <!-- Right Column: Sync & Config -->
            <div class="space-y-8">
                
                <!-- Auto-Sync Trigger -->
                <div class="bg-gradient-to-br from-blue-900/20 to-purple-900/20 border border-white/10 rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-2">Environment Sync</h2>
                    <p class="text-sm text-gray-400 mb-6">Manually trigger a sync of specific variables to all Vercel environments.</p>
                    
                    <form action="{{ route('dashboard.sync') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Variable Key</label>
                            <input type="text" name="env_key" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="e.g. DB_HOST">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Value</label>
                            <input type="text" name="env_value" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="Value...">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Target</label>
                            <select name="env_target" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-500 transition-colors">
                                <option value="production,preview,development">All Environments</option>
                                <option value="production">Production</option>
                                <option value="preview">Preview (Staging)</option>
                                <option value="development">Development</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-white text-black font-semibold py-2 rounded-lg hover:bg-gray-200 transition-colors">
                            Sync to Vercel
                        </button>
                    </form>
                </div>

                <!-- Permission Transparency -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <svg class="w-5 h-5 text-yellow-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <div>
                            <h2 class="font-semibold">Permission Transparency</h2>
                            <p class="text-xs text-gray-400 mt-1">Why do we need this Access Token?</p>
                        </div>
                    </div>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex gap-2">
                            <span class="text-green-500">✓</span>
                            <span><strong>Read Projects:</strong> To identify which project to sync variables to.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-green-500">✓</span>
                            <span><strong>Write Environment Variables:</strong> To safely push your encrypted secrets.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-green-500">✓</span>
                            <span><strong>Read Deployments:</strong> To show you build status and One-Click Previews.</span>
                        </li>
                    </ul>
                </div>

                @if($config)
                <div class="text-xs text-gray-600 font-mono text-center">
                    Connected to Project ID: {{ $config->project_id }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pollInterval = 5000; // 5 seconds
        
        function updateDeployments() {
            fetch('{{ route("deployments.status") }}')
                .then(response => response.json())
                .then(data => {
                    data.forEach(deployment => {
                        // Find row by some attribute or just by order if we assume order matches.
                        // Better: Add ID to rows.
                        const row = document.getElementById(`deployment-${deployment.id}`);
                        if (row) {
                            // Update State Badge
                            const stateCell = row.querySelector('.deployment-state');
                            if (stateCell) {
                                stateCell.innerHTML = `<span class="px-2 py-1 rounded text-xs font-semibold ${getStateClasses(deployment.state)}">${deployment.state}</span>`;
                            }
                            
                            // Update URL/Preview Link
                            const urlCell = row.querySelector('.deployment-url');
                            if (urlCell) {
                                if (deployment.url) {
                                    urlCell.innerHTML = `
                                        <a href="https://${deployment.url}" target="_blank" class="inline-flex items-center gap-1 text-blue-400 hover:text-white transition-colors group">
                                            <span>Visit Preview</span>
                                            <svg class="w-3 h-3 transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>`;
                                } else {
                                    urlCell.innerHTML = '<span class="text-gray-600">-</span>';
                                }
                            }

                             // Update Time
                             const timeCell = row.querySelector('.deployment-time');
                             if (timeCell) timeCell.textContent = deployment.ago;
                        } else {
                            // If row doesn't exist (new deployment), strictly speaking we should reload or prepend row.
                            // For MVP polling, we verify status of existing list.
                        }
                    });
                })
                .catch(err => console.error('Polling error:', err));
        }

        function getStateClasses(state) {
            switch(state) {
                case 'READY': return 'bg-green-500/20 text-green-400';
                case 'ERROR': return 'bg-red-500/20 text-red-400';
                case 'BUILDING': return 'bg-yellow-500/20 text-yellow-400';
                default: return 'bg-gray-500/20 text-gray-400';
            }
        }

        // Start polling if there are deployments
        if (document.querySelectorAll('[id^="deployment-"]').length > 0) {
            setInterval(updateDeployments, pollInterval);
        }
    });
</script>
@endsection
