<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Module_intervention extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function module():BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function intervention():BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
