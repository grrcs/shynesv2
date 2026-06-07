<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\User;

class AddressSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pembeli = User::where('email', 'pembeli@gmail.com')->first();

        if ($pembeli) {
            Address::firstOrCreate(
                ['user_id' => $pembeli->id, 'label' => 'Rumah'],
                [
                    'recipient_name' => 'Pembeli User',
                    'phone_number' => '081234567890',
                    'full_address' => 'Jl. Sudirman No. 123, RT 001/RW 002, Kelurahan Menteng, Kecamatan Jakarta Pusat',
                    'city' => 'Jakarta Pusat',
                    'province' => 'DKI Jakarta',
                    'postal_code' => '10350',
                    'is_primary' => true,
                ]
            );

            Address::firstOrCreate(
                ['user_id' => $pembeli->id, 'label' => 'Kantor'],
                [
                    'recipient_name' => 'Pembeli User',
                    'phone_number' => '081234567891',
                    'full_address' => 'Gedung Jaya Lt. 10, Jl. M.H. Thamrin No. 12, Jakarta Pusat',
                    'city' => 'Jakarta Pusat',
                    'province' => 'DKI Jakarta',
                    'postal_code' => '10220',
                    'is_primary' => false,
                ]
            );
        }
    }
}
