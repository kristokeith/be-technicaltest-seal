<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, UuidTrait, SoftDeletes;
    protected $fillable = ['uuid', 'name', 'description'];
    protected $primaryKey = 'uuid';
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
