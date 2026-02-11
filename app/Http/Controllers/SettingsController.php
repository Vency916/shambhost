<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VercelConfig;
use Illuminate\Support\Facades\Crypt;

class SettingsController extends Controller
{
    public function index()
    {
        $config = VercelConfig::where('user_id', auth()->id())->first();
        return view('settings', compact('config'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|string',
            'api_token' => 'required|string',
        ]);

        $config = VercelConfig::where('user_id', auth()->id())->first();

        if ($config) {
            $config->update([
                'project_id' => $request->project_id,
                'api_token' => Crypt::encryptString($request->api_token),
            ]);
        } else {
            VercelConfig::create([
                'user_id' => auth()->id(),
                'project_id' => $request->project_id,
                'api_token' => Crypt::encryptString($request->api_token),
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Vercel configuration updated successfully.');
    }

    public function destroy()
    {
        $config = VercelConfig::where('user_id', auth()->id())->first();

        if ($config) {
            $config->delete();
            return redirect()->route('settings.index')->with('success', 'Vercel integration disconnected successfully.');
        }

        return back()->with('error', 'No configuration found to disconnect.');
    }
}
