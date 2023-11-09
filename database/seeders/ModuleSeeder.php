<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contact = [
            [
                'name' => 'career-list',
                'active' => 1
            ],
            [
                'name' => 'career-create',
                'active' => 1
            ],
            [
                'name' => 'career-selection',
                'active' => 1
            ],
            [
                'name' => 'product-list',
                'active' => 1
            ],
            [
                'name' => 'product-create',
                'active' => 1
            ],
            [
                'name' => 'product-update',
                'active' => 1
            ],
            [
                'name' => 'customer-list',
                'active' => 1
            ],
            [
                'name' => 'customer-create',
                'active' => 1
            ],
            [
                'name' => 'customer-update',
                'active' => 1
            ],
            [
                'name' => 'user-list',
                'active' => 1
            ],
            [
                'name' => 'user-create',
                'active' => 1
            ],
            [
                'name' => 'user-update',
                'active' => 1
            ],
            [
                'name' => 'transaction-sales-list',
                'active' => 1
            ],
            [
                'name' => 'transaction-sales-list',
                'active' => 1
            ],
            [
                'name' => 'transaction-sales-create',
                'active' => 1
            ],
            [
                'name' => 'transaction-sales-update',
                'active' => 1
            ],
            [
                'name' => 'transaction-sales-make-invoice',
                'active' => 1
            ],
            [
                'name' => 'payment-ar-list',
                'active' => 1
            ],
        ];

        Module::insert($contact);
    }
}
