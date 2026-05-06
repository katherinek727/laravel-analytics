<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Joke extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'api_id',
        'type',
        'setup',
        'punchline',
        'raw_data',
        'fetched_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'raw_data' => 'array',
        'fetched_at' => 'datetime',
    ];

    /**
     * Get the attributes that should be hidden for serialization.
     *
     * @return array<int, string>
     */
    protected function hidden(): array
    {
        return [
            'raw_data',
            'created_at',
            'updated_at',
        ];
    }
}
