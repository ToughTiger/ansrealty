<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    use HasFactory;

    protected $fillable = [
        'communication_type',
        'direction',
        'communicable_type',
        'communicable_id',
        'user_id',
        'recipient_type',
        'recipient',
        'subject',
        'message',
        'status',
        'sent_at',
        'delivered_at',
        'read_at',
        'metadata',
        'template_id',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function communicable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(CommunicationTemplate::class, 'template_id');
    }
}
