<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Membuat dua peran: Admin (akses penuh) dan Staf (akses terbatas).
     * Aman dijalankan berulang karena memakai firstOrCreate dan syncPermissions.
     */
    public function run(): void
    {
        // Bersihkan cache izin agar perubahan langsung terbaca
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $lihatData = Permission::firstOrCreate(['name' => 'lihat-data', 'guard_name' => 'web']);
        $hapusData = Permission::firstOrCreate(['name' => 'hapus-data', 'guard_name' => 'web']);

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $staf = Role::firstOrCreate(['name' => 'Staf', 'guard_name' => 'web']);

        $admin->syncPermissions([$lihatData, $hapusData]); // akses penuh
        $staf->syncPermissions([$lihatData]);              // hanya melihat
    }
}
