<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, UuidTrait, SoftDeletes;

    protected $fillable = ['uuid', 'project_uuid', 'name', 'description', 'due_date'];
    protected $primaryKey = 'uuid';

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
