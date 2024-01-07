<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'grade',
        'pack_id',
        'repo_id',
        'in_repo',
        'in_shop',
        'price',
        'auction_price',
        'in_auction',
        'weight',
        'description',
    ];

    public function repository()
    {
        return $this->belongsTo(Repository::class, 'repo_id');
    }
}
