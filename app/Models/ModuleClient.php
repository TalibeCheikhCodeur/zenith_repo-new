<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleClient extends Model
{

    use HasFactory;
    protected $guarded=[];
    public function module():BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
