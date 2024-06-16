<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = ['platform_name', 'token_value', 'exchange_rate'];

    public function periods()
    {
        return $this->hasMany(Period::class);
    }
}
