<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = ['type', 'start_time', 'end_time', 'sort_order'];

    public function getLabelAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
    }
}
