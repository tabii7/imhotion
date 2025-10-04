<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BaseSeed extends Seeder
{
    public function run(): void
    {
        // Default price per day (in cents)
        DB::table('settings')->updateOrInsert(
            ['key' => 'day_price_cents'],
            ['value' => '35000'] // €350.00
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@imhotion.com'],
            [
                'name'         => 'Admin',
                'full_name'    => 'Imhotion Admin',
                'role'         => 'admin',
                'balance_days' => 10,
                'password'     => Hash::make('password'),
            ]
        );

        $client1 = User::updateOrCreate(
            ['email' => 'client1@imhotion.com'],
            [
                'name'         => 'Client One',
                'full_name'    => 'Client One',
                'role'         => 'client',
                'balance_days' => 10,
                'password'     => Hash::make('password'),
            ]
        );

        $client2 = User::updateOrCreate(
            ['email' => 'client2@imhotion.com'],
            [
                'name'         => 'Client Two',
                'full_name'    => 'Client Two',
                'role'         => 'client',
                'balance_days' => 10,
                'password'     => Hash::make('password'),
            ]
        );

        foreach ([$client1, $client2] as $c) {
            Project::firstOrCreate([
                'user_id' => $c->id,
                'name'    => 'Brand Identity Redesign',
            ], [
                'topic'        => 'Design System',
                'status'       => 'completed',
                'delivery_date'=> now()->addMonths(1)->toDateString(),
                'progress'     => 100,
            ]);

            Project::firstOrCreate([
                'user_id' => $c->id,
                'name'    => 'E-commerce Platform',
            ], [
                'topic'        => 'Web Development',
                'status'       => 'in_progress',
                'delivery_date'=> now()->addWeeks(3)->toDateString(),
                'progress'     => 75,
            ]);
        }
    }
}
