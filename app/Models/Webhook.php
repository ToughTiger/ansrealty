<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Webhook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'endpoint',
        'verify_token',
        'status',
        'description',
        'total_calls',
        'successful_calls',
        'failed_calls',
        'last_called_at',
    ];

    protected $casts = [
        'last_called_at' => 'datetime',
    ];

    public function getSuccessRateAttribute()
    {
        if ($this->total_calls === 0) {
            return 0;
        }
        
        return round(($this->successful_calls / $this->total_calls) * 100, 2);
    }
}
