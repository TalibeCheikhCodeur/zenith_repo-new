<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleIntervention extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function moduleClient(): BelongsTo
    {
        return $this->belongsTo(ModuleClient::class);
    }

    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
