<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        $customer = Customer::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'total_poin' => 0,
        ]);

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }

    public function show(Customer $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    public function search($phone)
    {
        // Trim dan sanitize input
        $phone = trim($phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Log untuk debugging
        \Log::info('Searching customer - Phone: [' . $phone . ']');

        // Cari semua customer yang phone_number-nya mengandung input (minimal 3 digit)
        $customers = [];
        if (strlen($phone) >= 3) {
            $customers = Customer::where('phone_number', 'like', '%' . $phone . '%')
                ->select('id', 'name', 'phone_number', 'total_poin')
                ->limit(10) // Batasi hasil agar tidak terlalu banyak
                ->get();

            \Log::info('Found ' . $customers->count() . ' customers');
        }

        return response()->json([
            'customers' => $customers
        ]);
    }
}
