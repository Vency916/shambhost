@extends('layouts.app')

@section('content')
<div class="py-12 bg-black min-h-screen text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold tracking-tight">Build Logs</h1>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-white">&larr; Back to Dashboard</a>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden font-mono text-sm">
            <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex justify-between items-center">
                <span class="text-gray-400">Deployment ID: <span class="text-white">{{ $id }}</span></span>
                <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs">Vercel API</span>
            </div>
            
            <div class="p-6 max-h-[600px] overflow-y-auto space-y-2">
                @if(empty($logs))
                    <div class="text-gray-500 italic">No logs available for this deployment.</div>
                @else
                    @foreach($logs as $log)
                        <div class="flex gap-4 group hover:bg-white/5 p-1 -mx-2 rounded transition-colors">
                            <span class="text-gray-600 w-32 shrink-0 select-none">{{ isset($log['date']) ? \Carbon\Carbon::parse($log['date'] / 1000)->format('H:i:s.u') : '--:--' }}</span>
                            <span class="{{ isset($log['type']) && $log['type'] === 'stderr' ? 'text-red-400' : 'text-gray-300' }}">
                                {{ $log['text'] ?? json_encode($log) }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
