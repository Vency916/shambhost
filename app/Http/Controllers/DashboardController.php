<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deployment;
use App\Models\VercelConfig;
use App\Jobs\SyncVercelEnvironment;
use App\Services\VercelOrchestrator;
use Illuminate\Support\Facades\Crypt;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $config = VercelConfig::where('user_id', $user->id)->first();
        
        // Mock data for MVP if no config exists
        $deployments = Deployment::where('user_id', $user->id)->latest()->take(5)->get();
        
        // Mock Health Check
        $backendLatency = rand(20, 50); // ms
        $vercelStatus = 'Operational'; // In real world, fetch from Vercel Status API

        return view('dashboard', compact('deployments', 'config', 'backendLatency', 'vercelStatus'));
    }

    public function refresh()
    {
        // Ideally pass user_id to job, but for now dispatchSync handles it if we update job
        $config = VercelConfig::where('user_id', auth()->id())->first();
        if ($config) {
             \App\Jobs\FetchVercelDeployments::dispatchSync($config->project_id);
        }
        return back()->with('success', 'Deployment status refreshed from Vercel.');
    }

    public function sync(Request $request)
    {
        $request->validate([
            'env_key' => 'required|string',
            'env_value' => 'required|string',
        ]);

        $config = VercelConfig::where('user_id', auth()->id())->first();
        
        if (!$config) {
            return redirect()->route('settings.index')->with('error', 'Please configure your Vercel credentials first.');
        }

        // Dispatch Job (Async to prevent 60s timeout)
        $targets = explode(',', $request->input('env_target', 'production,preview'));
        
        SyncVercelEnvironment::dispatch(
            $config->project_id, 
            [$request->env_key => $request->env_value], 
            $targets
        );

        return back()->with('success', 'Sync initiated in background. Deployment will follow shortly.');
    }

    public function status()
    {
        $config = VercelConfig::where('user_id', auth()->id())->first();
        if (!$config) return response()->json([]);

        // Fetch latest data from Vercel to ensure it's fresh
        // In a real app, we might use cache or DB, but for "Real-time" polling, fetching from source is safer
        // provided we don't hit rate limits. For MVP, we'll sync first then return DB.
        
        try {
            \App\Jobs\FetchVercelDeployments::dispatchSync($config->project_id);
        } catch (\Exception $e) {
            // Ignore fetch errors during poll
        }

        $deployments = Deployment::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get(['id', 'vercel_deployment_id', 'state', 'url', 'created_at_vercel']) // Select only needed fields
            ->map(function ($d) {
                return [
                    'id' => $d->vercel_deployment_id,
                    'state' => $d->state,
                    'url' => $d->url,
                    'ago' => $d->created_at_vercel ? $d->created_at_vercel->diffForHumans() : 'Just now'
                ];
            });

        return response()->json($deployments);
    }

    public function logs($id)
    {
        $config = VercelConfig::where('user_id', auth()->id())->firstOrFail();
        
        $orchestrator = new VercelOrchestrator($config->project_id);
        $logs = $orchestrator->getDeploymentLogs($id);

        return view('deployments.logs', compact('logs', 'id'));
    }
}
