<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intervention extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return  $this->belongsTo(User::class,"user_id");
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class,'module_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
