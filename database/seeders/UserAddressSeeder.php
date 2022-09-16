<?php

namespace Database\Seeders;

use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $address = UserAddress::create([
            'user_id' => 1,
            'full_address' => 'Jl. H.R. Rasuna Said blok EE no.6 RT 07/003 Kel. Kuningan Timur', 
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Setia Budi',
            'zipcode' => 12950,
        ]);
    }
}
