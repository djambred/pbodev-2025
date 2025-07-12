<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $table = 'customers';
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
