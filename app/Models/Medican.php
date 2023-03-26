<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medican extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'name',
        'price',
        'description',
        'quantity',
    ];

    public $timestamps = false;
}
