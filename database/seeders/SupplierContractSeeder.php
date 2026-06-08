<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\DistributorContract;
use App\Services\ContractEncryptionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupplierContractSeeder extends Seeder
{
    protected array $contracts = [];

    public function __construct()
    {
        $this->contracts = [
            [
                'code' => 'DK01',
                'supplier' => 'PT. Fashion Nusantara Sejahtera',
                'contact' => 'Bambang Supriyadi',
                'email' => 'bambang@fashionnusantara.co.id',
                'phone' => '0812-3456-7890',
                'address' => 'Jl. MT Haryono No. 15, Jakarta Timur, DKI Jakarta 13630',
                'distributor' => 'CV. Distribusi Busana Kita',
                'distributor_contact' => 'Dewi Sartika',
                'start' => '2026-01-01',
                'end' => '2026-12-31',
                'value' => 500000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK02',
                'supplier' => 'CV. Busana Muslim Indonesia',
                'contact' => 'Ahmad Fauzi',
                'email' => 'ahmad@busanamuslim.co.id',
                'phone' => '0821-1234-5678',
                'address' => 'Jl. Sukajadi No. 45, Bandung, Jawa Barat 40161',
                'distributor' => 'PT. Fashion Distribution Center',
                'distributor_contact' => 'Rina Marlina',
                'start' => '2026-02-01',
                'end' => '2026-11-30',
                'value' => 350000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK03',
                'supplier' => 'UD. Kain Nusantara Jaya',
                'contact' => 'Siti Rahmawati',
                'email' => 'siti@kainnusantara.co.id',
                'phone' => '0857-8901-2345',
                'address' => 'Jl. Malioboro No. 120, Yogyakarta 55271',
                'distributor' => 'Toko Busana Serba Ada',
                'distributor_contact' => 'Hendra Gunawan',
                'start' => '2026-03-01',
                'end' => '2026-08-31',
                'value' => 175000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK04',
                'supplier' => 'PT. Garmen Sejahtera Mandiri',
                'contact' => 'Dwi Hartanto',
                'email' => 'dwi@garmensejahtera.co.id',
                'phone' => '0813-4567-8901',
                'address' => 'Jl. Raya Bogor KM 28, Jakarta Timur 13710',
                'distributor' => 'CV. Fashion Distributor Nusantara',
                'distributor_contact' => 'Lina Fitriani',
                'start' => '2026-01-15',
                'end' => '2026-12-31',
                'value' => 625000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK05',
                'supplier' => 'CV. Konveksi Cipta Busana',
                'contact' => 'Agus Wijaya',
                'email' => 'agus@ciptabusana.co.id',
                'phone' => '0822-5678-9012',
                'address' => 'Jl. Gatot Subroto No. 200, Semarang, Jawa Tengah 50128',
                'distributor' => 'PT. Pusat Distribusi Fashion',
                'distributor_contact' => 'Budi Santoso',
                'start' => '2026-04-01',
                'end' => '2026-09-30',
                'value' => 450000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK06',
                'supplier' => 'PT. Tekstil Nusantara Prima',
                'contact' => 'Rudi Hermawan',
                'email' => 'rudi@tekstilnusantara.co.id',
                'phone' => '0856-7890-1234',
                'address' => 'Jl. Industri No. 88, Tangerang, Banten 15114',
                'distributor' => 'CV. Grosir Busana Nasional',
                'distributor_contact' => 'Maya Anggraini',
                'start' => '2026-02-15',
                'end' => '2026-10-31',
                'value' => 280000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK07',
                'supplier' => 'UD. Fashion Indah',
                'contact' => 'Ratna Dewi',
                'email' => 'ratna@fashionindah.co.id',
                'phone' => '0811-2222-3333',
                'address' => 'Jl. Diponegoro No. 55, Surabaya, Jawa Timur 60241',
                'distributor' => 'Toko Busana Murah Meriah',
                'distributor_contact' => 'Arief Prasetyo',
                'start' => '2025-06-01',
                'end' => '2026-05-31',
                'value' => 150000000,
                'status' => 'expired',
            ],
            [
                'code' => 'DK08',
                'supplier' => 'CV. Jahit Rapi Sejahtera',
                'contact' => 'Hendra Kusuma',
                'email' => 'hendra@jahitrapi.co.id',
                'phone' => '0814-5678-9012',
                'address' => 'Jl. Merdeka No. 77, Medan, Sumatera Utara 20151',
                'distributor' => 'PT. Distributor Fashion Utama',
                'distributor_contact' => 'Sari Puspita',
                'start' => '2026-05-01',
                'end' => '2026-12-31',
                'value' => 520000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK09',
                'supplier' => 'PT. Pakaian Jadi Nasional',
                'contact' => 'Indra Lesmana',
                'email' => 'indra@pakaianjadi.co.id',
                'phone' => '0877-1234-5678',
                'address' => 'Jl. Sudirman No. 33, Makassar, Sulawesi Selatan 90111',
                'distributor' => 'CV. Busana Kita Bersama',
                'distributor_contact' => 'Fitri Handayani',
                'start' => '2026-03-15',
                'end' => '2026-12-15',
                'value' => 390000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK10',
                'supplier' => 'UD. Konveksi Barokah',
                'contact' => 'Hasan Basri',
                'email' => 'hasan@konveksibarokah.co.id',
                'phone' => '0815-9012-3456',
                'address' => 'Jl. Pahlawan No. 12, Malang, Jawa Timur 65111',
                'distributor' => 'Toko Fashion Keluarga',
                'distributor_contact' => 'Nurul Hidayah',
                'start' => '2025-01-01',
                'end' => '2025-12-31',
                'value' => 125000000,
                'status' => 'terminated',
            ],
            [
                'code' => 'DK11',
                'supplier' => 'CV. Mode Fashion Indonesia',
                'contact' => 'Yusuf Permadi',
                'email' => 'yusuf@modefashion.co.id',
                'phone' => '0816-2345-6789',
                'address' => 'Jl. Asia Afrika No. 150, Bandung, Jawa Barat 40171',
                'distributor' => 'PT. Distributor Utama Busana',
                'distributor_contact' => 'Ani Susanti',
                'start' => '2026-06-01',
                'end' => '2026-12-31',
                'value' => 475000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK12',
                'supplier' => 'PT. Bintang Busana Sejahtera',
                'contact' => 'Teguh Prakoso',
                'email' => 'teguh@bintangbusana.co.id',
                'phone' => '0817-3456-7890',
                'address' => 'Jl. Sunter Paradise No. 88, Jakarta Utara 14350',
                'distributor' => 'CV. Pusat Busana Nasional',
                'distributor_contact' => 'Deni Maulana',
                'start' => '2026-01-01',
                'end' => '2026-12-31',
                'value' => 600000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK13',
                'supplier' => 'UD. Karya Mode Indah',
                'contact' => 'Wahyu Hidayat',
                'email' => 'wahyu@karyamode.co.id',
                'phone' => '0818-4567-8901',
                'address' => 'Jl. Kopi No. 25, Denpasar, Bali 80231',
                'distributor' => 'Toko Busana Modern',
                'distributor_contact' => 'Putu Ayu',
                'start' => '2026-07-01',
                'end' => '2026-12-31',
                'value' => 200000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK14',
                'supplier' => 'CV. Garmen Profesional',
                'contact' => 'Adi Nugroho',
                'email' => 'adi@garmenprofesional.co.id',
                'phone' => '0819-5678-9012',
                'address' => 'Jl. Veteran No. 44, Solo, Jawa Tengah 57121',
                'distributor' => 'PT. Fashion Link Indonesia',
                'distributor_contact' => 'Eva Marlina',
                'start' => '2026-04-01',
                'end' => '2026-12-31',
                'value' => 340000000,
                'status' => 'active',
            ],
            [
                'code' => 'DK15',
                'supplier' => 'PT. Fashion Kreatif Mandiri',
                'contact' => 'Reza Pratama',
                'email' => 'reza@fashionkreatif.co.id',
                'phone' => '0820-6789-0123',
                'address' => 'Jl. Boulevard Raya No. 1, Bekasi, Jawa Barat 17141',
                'distributor' => 'CV. Distribusi Mode Nasional',
                'distributor_contact' => 'Irfan Hakim',
                'start' => '2026-05-15',
                'end' => '2026-12-31',
                'value' => 550000000,
                'status' => 'active',
            ],
        ];
    }

    public function run(): void
    {
        // Hapus data lama (hard delete + reset auto-increment)
        Schema::disableForeignKeyConstraints();
        DB::table('distributor_contracts')->whereIn('contract_code', array_column($this->contracts, 'code'))->delete();
        DB::statement('ALTER TABLE distributor_contracts AUTO_INCREMENT = 1');
        Supplier::withoutGlobalScope('tenant')->where('email', 'like', '%@test.com')->delete();
        User::where('email', 'like', 'supplier%')->delete();
        Schema::enableForeignKeyConstraints();
        Storage::disk('contracts')->deleteDirectory('suppliers');

        $encryptionService = app(ContractEncryptionService::class);

        foreach ($this->contracts as $data) {
            $tenantId = (string) Str::uuid();
            $email = 'supplier' . ((int) substr($data['code'], 2)) . '@test.com';

            // Step 1: Register user as pembeli
            $user = User::create([
                'name' => $data['contact'],
                'email' => $email,
                'role' => 'pembeli',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            // Step 2: Register supplier (status pending)
            $supplier = Supplier::withoutGlobalScope('tenant')->create([
                'user_id' => $user->id,
                'company_name' => $data['supplier'],
                'contact_person' => $data['contact'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'status' => 'pending',
            ]);

            // Step 3: Admin approves supplier
            $supplier->update([
                'status' => 'active',
                'tenant_id' => $tenantId,
                'approved_at' => now(),
            ]);

            $user->update([
                'role' => 'supplier',
                'tenant_id' => $tenantId,
            ]);

            // Step 4: Buat file kontrak terenkripsi & simpan di DB
            $contractJson = json_encode([
                'contract_code' => $data['code'],
                'supplier' => [
                    'company_name' => $data['supplier'],
                    'contact_person' => $data['contact'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                ],
                'distributor' => $data['distributor'],
                'distributor_contact' => $data['distributor_contact'],
                'contract_period' => [
                    'start' => $data['start'],
                    'end' => $data['end'],
                ],
                'contract_value' => $data['value'],
                'status' => $data['status'],
            ], JSON_PRETTY_PRINT);

            $encrypted = $encryptionService->encrypt($contractJson, $tenantId);
            $filePath = 'suppliers/' . $data['code'] . '.enc';
            Storage::disk('contracts')->put($filePath, $encrypted['encrypted']);

            // Step 5: Create distributor contract
            DistributorContract::create([
                'contract_code' => $data['code'],
                'supplier_id' => $supplier->id,
                'distributor_company' => $data['distributor'],
                'distributor_contact' => $data['distributor_contact'],
                'contract_start_date' => $data['start'],
                'contract_end_date' => $data['end'],
                'contract_value' => $data['value'],
                'status' => $data['status'],
                'file_path' => $filePath,
                'encrypted_data' => $encrypted['encrypted'],
                'encryption_key_hash' => $encrypted['key_hash'],
                'tenant_id' => $tenantId,
            ]);

            $this->command->info("Created: {$data['code']} - {$data['supplier']}");
        }

        $this->command->info('');
        $this->command->info('=== DEMO CREDENTIALS ===');
        $this->command->info('Admin: admin@gmail.com / password');
        for ($i = 1; $i <= 15; $i++) {
            $code = 'DK' . str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $this->command->info("Supplier {$code}: supplier{$i}@test.com / password");
        }
    }
}
