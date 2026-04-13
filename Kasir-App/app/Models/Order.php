<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'employee_id',
        'customer_id',
        'sale_date',
        'total_price',
        'total_pay',
        'total_return',
        'points_earned',
        'points_used',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}
