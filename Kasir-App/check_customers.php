<?php

require 'vendor/autoload.php';

use App\Models\Customer;

$customers = Customer::all();

echo "=== LIST CUSTOMER ===" . PHP_EOL;
echo "Total: " . $customers->count() . PHP_EOL . PHP_EOL;

foreach ($customers as $customer) {
    echo "ID: " . $customer->id . PHP_EOL;
    echo "Name: " . $customer->name . PHP_EOL;
    echo "Phone: " . $customer->phone_number . PHP_EOL;
    echo "Points: " . $customer->total_poin . PHP_EOL;
    echo "---" . PHP_EOL;
}
