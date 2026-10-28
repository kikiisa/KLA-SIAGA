<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OpdSeeder::class,
            RolePermissionSeeder::class,
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@siagakla.go.id'],
            [
                'name' => 'Andi Kurniawan',
                'password' => 'siagakla2026',
                'is_active' => true,
            ],
        );

        $admin->assignRole('admin');
    }
}
