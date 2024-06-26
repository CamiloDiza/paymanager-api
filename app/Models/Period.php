<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date', 'platform_id'];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function income()
    {
        return $this->hasMany(Period::class);
    }
}
