<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'category',
        'subject',
        'body',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function communications()
    {
        return $this->hasMany(Communication::class, 'template_id');
    }

    public function render(array $data = []): string
    {
        $body = $this->body;

        foreach ($data as $key => $value) {
            $body = str_replace('{' . $key . '}', $value, $body);
        }

        return $body;
    }
}
