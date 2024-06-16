<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = ['receiver_id', 'period_id', 'tokens', 'total_usd', 'total_cop'];

    public function receiver()
    {
        return $this->belongsTo(Platform::class);
    }

    public function period()
    {
        return $this->belongsTo(Platform::class);
    }
}
