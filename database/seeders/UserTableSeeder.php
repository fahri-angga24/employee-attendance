<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin App',
            'username' => 'admin', 
            'email' => 'fahriangga98@gmail.com',
            'phone' => '081287627241',
            'password' => Hash::make('admin123@@'),
        ]);
    }
}
