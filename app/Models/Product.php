<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'price',
        'stock',
        'image',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}
