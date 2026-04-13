<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\Auth;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Order::with(['customer', 'employee', 'detailOrders.product']);

        if (Auth::check() && Auth::user()->role === 'employee') {
            $query->where('employee_id', Auth::id());
        }

        return $query->orderBy('sale_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Employee',
            'Customer',
            'Phone Number',
            'Sale Date',
            'Total Price',
            'Total Pay',
            'Change',
            'Points Used',
            'Points Earned',
            'Products',
            'Quantity'
        ];
    }

    public function map($order): array
    {
        $products = '';
        $quantity = 0;

        foreach ($order->detailOrders as $detail) {
            $products .= $detail->product->name . ' (' . $detail->amount . 'x), ';
            $quantity += $detail->amount;
        }

        return [
            $order->id,
            $order->employee->name,
            $order->customer ? $order->customer->name : 'Non-Member',
            $order->customer ? $order->customer->phone_number : '-',
            $order->sale_date->format('Y-m-d H:i:s'),
            'Rp ' . number_format($order->total_price, 0, ',', '.'),
            'Rp ' . number_format($order->total_pay, 0, ',', '.'),
            'Rp ' . number_format($order->total_return, 0, ',', '.'),
            $order->points_used,
            $order->points_earned,
            rtrim($products, ', '),
            $quantity
        ];
    }

    public function title(): string
    {
        return 'Orders Report';
    }
}
