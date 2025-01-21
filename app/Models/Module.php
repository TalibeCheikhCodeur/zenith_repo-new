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
        return $this->belongsToMany(User::class, 'module_clients')->withPivot(['numero_serie', 'version', 'code_annuel', 'code_activation', 'nbre_users', 'nbre_salariés', 'date_fin_validite']);
    }
    
    public function module_interventions(): HasMany
    {
        return $this->hasMany(ModuleIntervention::class, 'module_id');
    }

    public function gamme(): BelongsTo
    {
        return $this->belongsTo(Gamme::class, 'gamme_id');
    }

    public function module_client()
    {
        return $this->hasMany(ModuleClient::class);
    }

}
