<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products - All roles can view
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Orders - All roles
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Employee/Kasir routes
    Route::middleware(['role:employee'])->group(function () {
        Route::get('/transaction/create', [OrderController::class, 'create'])->name('transaction.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/employee', function () {
            return redirect()->route('transaction.create');
        })->name('employee.dashboard');
    });

    // Customer search (accessible by both admin and employee)
    Route::get('/customers/search/{phone}', [CustomerController::class, 'search'])->name('customers.search');

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', function () {
            return redirect()->route('dashboard');
        })->name('admin.dashboard');

        Route::prefix('admin/users')->name('admin.users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/create', [UserManagementController::class, 'create'])->name('create');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        });

        // Product Management
        Route::prefix('admin/products')->name('admin.products.')->group(function () {
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::post('/{product}/update-stock', [ProductController::class, 'updateStock'])->name('update-stock');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });
    });

    // Debug route - cek semua customer
    Route::get('/debug/customers', function() {
        $customers = \App\Models\Customer::all();
        $output = "Total Customers: " . $customers->count() . "\n\n";
        foreach ($customers as $c) {
            $output .= "ID: " . $c->id . "\n";
            $output .= "Name: " . $c->name . "\n";
            $output .= "Phone: [" . $c->phone_number . "]\n";
            $output .= "Points: " . $c->total_poin . "\n";
            $output .= "---\n";
        }
        return "<pre>" . $output . "</pre>";
    })->name('debug.customers');

    // Debug route - test search
    Route::get('/debug/search/{phone}', function($phone) {
        $customer = \App\Models\Customer::where('phone_number', $phone)->first();

        $output = "Searching for phone: [" . $phone . "]\n\n";

        if ($customer) {
            $output .= "✓ CUSTOMER FOUND!\n";
            $output .= "ID: " . $customer->id . "\n";
            $output .= "Name: " . $customer->name . "\n";
            $output .= "Phone: " . $customer->phone_number . "\n";
            $output .= "Points: " . $customer->total_poin . "\n";
        } else {
            $output .= "✗ CUSTOMER NOT FOUND\n\n";

            // Tampilkan semua phone numbers
            $allPhones = \App\Models\Customer::pluck('phone_number')->toArray();
            $output .= "All phone numbers in DB:\n";
            foreach ($allPhones as $p) {
                $output .= "  - [" . $p . "]\n";
            }
        }

        return "<pre>" . $output . "</pre>";
    })->name('debug.search');
});
