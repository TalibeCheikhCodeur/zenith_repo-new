<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class,'module_clients');
    }
    public function module_interventions(): HasMany
    {
        return $this->hasMany(Module_intervention::class,'module_id');
    }
      
    public function gamme(): BelongsTo
    {
        return $this->belongsTo(Gamme::class,'gamme_id');
     }
    
}
