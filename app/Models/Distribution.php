<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = ['income_id', 'receiver_percentage', 'payer_percentage', 'receiver_amount_cop', 'payer_amount_cop'];

    public function income()
    {
        return $this->belongsTo(Platform::class);
    }
}
