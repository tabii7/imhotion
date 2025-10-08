<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample teams
        $teams = [
            [
                'name' => 'Frontend Development Team',
                'description' => 'Specialized in React, Vue.js, and modern frontend technologies',
                'status' => 'active',
                'specializations' => ['react', 'vue', 'javascript', 'css'],
            ],
            [
                'name' => 'Backend Development Team',
                'description' => 'Expert in Laravel, Node.js, and server-side technologies',
                'status' => 'active',
                'specializations' => ['laravel', 'nodejs', 'php', 'python'],
            ],
            [
                'name' => 'Mobile Development Team',
                'description' => 'iOS and Android app development specialists',
                'status' => 'active',
                'specializations' => ['ios', 'android', 'react-native', 'flutter'],
            ],
            [
                'name' => 'DevOps Team',
                'description' => 'Infrastructure, deployment, and cloud management',
                'status' => 'active',
                'specializations' => ['aws', 'docker', 'kubernetes', 'ci-cd'],
            ],
        ];

        foreach ($teams as $teamData) {
            Team::create($teamData);
        }

        // Assign some users to teams if they exist
        $users = User::where('role', 'developer')->take(4)->get();
        $teams = Team::all();

        foreach ($users as $index => $user) {
            if (isset($teams[$index])) {
                $teams[$index]->members()->attach($user->id, [
                    'role' => $index === 0 ? 'lead' : 'member',
                    'joined_at' => now(),
                ]);
            }
        }
    }
}