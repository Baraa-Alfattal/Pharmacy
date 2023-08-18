<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacie_id');
    }
}
