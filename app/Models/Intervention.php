<?php

namespace App\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intervention extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_interventions');
    }

    public function moduleIntervention(): HasMany
    {
        return $this->hasMany(Module_intervention::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
