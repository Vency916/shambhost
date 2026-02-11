<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VercelConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'api_token',
        'environment_mapping',
        'last_synced_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'environment_mapping' => 'array',
        'last_synced_at' => 'datetime',
    ];
}
