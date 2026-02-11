<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vercel_deployment_id',
        'project_id',
        'url',
        'state',
        'branch',
        'created_at_vercel',
    ];

    protected $casts = [
        'created_at_vercel' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
