<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plate_number',
        'agency_id',

    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }
}
