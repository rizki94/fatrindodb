<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AccountSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(ContactSeeder::class);
        $this->call(ContactTypeSeeder::class);
        $this->call(JobSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(PrefixSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProfileSeeder::class);
        $this->call(QualificationSeeder::class);
        $this->call(TaxSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProductBrandSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductDivisionSeeder::class);
    }
}
