<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function getProgressPercentageAttribute(): float
    {
        $total = Task::whereHas('milestone', fn ($q) => $q->where('project_id', $this->id))->count();
        if ($total === 0) {
            return 0;
        }
        $completed = Task::whereHas('milestone', fn ($q) => $q->where('project_id', $this->id))
            ->where('status', 'completed')
            ->count();
        return round(($completed / $total) * 100, 1);
    }
}
