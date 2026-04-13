<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'employee', 'detailOrders.product'])
            ->orderBy('sale_date', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('employee.transaction', compact('products'));
    }

    public function store(Request $request)
    {
        // Validate base request
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_type' => 'required|in:existing-member,new-member,non-member',
            'customer_id' => 'nullable|string',
            'use_points' => 'boolean',
            'total_pay' => 'required|integer|min:0',
        ]);

        // Additional validation for new member
        if ($request->customer_type === 'new-member') {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20|unique:customers,phone_number',
            ]);
        }

        try {
            DB::beginTransaction();

            // Calculate total price
            $totalPrice = 0;
            $items = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: {$product->name}");
                }
                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal
                ];
            }

            // Handle customer
            $customerId = null;
            $pointsUsed = 0;
            $pointsEarned = 0;

            if ($request->customer_type === 'existing-member') {
                // Existing member
                $customer = Customer::find($request->customer_id);
                if (!$customer) {
                    throw new \Exception('Customer tidak ditemukan');
                }
                $customerId = $customer->id;

                if ($request->use_points && $customer->total_poin > 0) {
                    $pointsUsed = $customer->total_poin;
                    $totalPrice -= $pointsUsed;
                    if ($totalPrice < 0) $totalPrice = 0;
                }

                $pointsEarned = intval($totalPrice * 0.01);
                $customer->update([
                    'total_poin' => $customer->total_poin - $pointsUsed + $pointsEarned
                ]);
            } elseif ($request->customer_type === 'new-member') {
                // New member - create with validated data
                $customer = Customer::create([
                    'id' => Str::uuid(),
                    'name' => $request->customer_name,
                    'phone_number' => $request->customer_phone,
                    'total_poin' => intval($totalPrice * 0.01)
                ]);
                $customerId = $customer->id;
                $pointsEarned = $customer->total_poin;
            }

            $totalReturn = $request->total_pay - $totalPrice;

            // Create order
            $order = Order::create([
                'id' => Str::uuid(),
                'employee_id' => auth()->id(),
                'customer_id' => $customerId,
                'sale_date' => now(),
                'total_price' => $totalPrice,
                'total_pay' => $request->total_pay,
                'total_return' => $totalReturn,
                'points_earned' => $pointsEarned,
                'points_used' => $pointsUsed,
            ]);

            // Create detail orders and update stock
            foreach ($items as $item) {
                DetailOrder::create([
                    'product_id' => $item['product']->id,
                    'order_id' => $order->id,
                    'amount' => $item['quantity'],
                    'sub_total' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Transaksi berhasil'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'employee', 'detailOrders.product']);
        return view('orders.show', compact('order'));
    }

    public function export(Request $request)
    {
        $filename = 'orders_report_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new OrdersExport, $filename);
    }
}
