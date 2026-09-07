<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $guarded = [];

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }
}
