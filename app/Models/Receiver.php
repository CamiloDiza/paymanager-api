<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    use HasFactory;

    protected $fillable = ['receiver_name', 'document_type', 'receiver_id', 'bank', 'bank_account', 'receiver_percentage'];

    public function income()
    {
        return $this->hasMany(Period::class);
    }
}
