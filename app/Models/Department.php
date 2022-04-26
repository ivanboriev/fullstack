<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'started_at'
    ];

    public $casts = [
        'started_at' => 'date:Y-m-d'
    ];

    public function workers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Worker::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
