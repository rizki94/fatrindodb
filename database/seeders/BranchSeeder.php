<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches')->insert([
            'code' => 'BDG',
            'company_id' => Company::first()->id,
            'name' => 'BANDUNG',
            'active' => 1
        ]);
    }
}
