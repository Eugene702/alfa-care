<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = ['name', 'position'];

    public function ccoRecording(): HasMany{
        return $this->hasMany(CcoRecording::class, 'agentId');
    }

    public function cco(): HasMany{
        return $this->hasMany(Cco::class, 'agentId');
    }

    public function cho(): HasMany{
        return $this->hasMany(Cho::class, 'agentId');
    }
}
