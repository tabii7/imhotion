<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->where('email', 'admin@imhotion.com')->update([
            'role' => 'admin',
        ]);
    }
}
