<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $fillable = ['name', 'price', 'stock_qty', 'booth_id', 'photo'];

    public function booth(): BelongsTo
    {
        return $this->belongsTo(Booth::class);
    }
}
