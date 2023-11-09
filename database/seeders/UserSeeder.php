<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::truncate();

        $user = [
            'name' => 'rizki',
            'full_name' => 'Rizki Rinaldi Syamsuri',
            'password' => bcrypt('ngalagena'),
            'is_salesman' => 1,
            'branch_id' => 'BDG',
            'active' => 1,
            'remember_token' => Str::random(10),
        ];

        User::insert($user);
    }
}
