<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'code' => 'REG',
            'name' => 'REGULAR COMPANY',
            'address' => 'JALAN PAGARSIH BARAT NO. 361-363 RT 001/001 KEL. BABAKAN KEC. CIPARAY KOTA. BANDUNG PROV. JAWA BARAT INDONESIA 40221',
            'phone' => '022-6037966',
            'email' => 'it.hrdistribusindo@gmail.com',
            'tax_id' => '31.691.012.4-422.000',
            'tax_name' => 'CV HR DISTRIBUSINDO',
            'tax_address' => 'JALAN PAGARSIH BARAT NO. 363 RT 01 RW 01 BABAKAN-BABAKAN CIPARAY BANDUNG',
            'active' => 1
        ]);
    }
}
