<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a specialization for developers
        $specialization = Specialization::first();
        
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@imhotion.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_available' => true,
                'phone' => '+1 (555) 000-0001',
                'address' => '123 Admin Street',
                'city' => 'Admin City',
                'country' => 'US',
                'bio' => 'System administrator with full access to all features.',
                'portfolio_url' => 'https://admin.imhotion.com',
                'linkedin_url' => 'https://linkedin.com/in/admin',
                'github_url' => 'https://github.com/admin',
                'skills' => ['System Administration', 'Database Management', 'Security', 'Project Management', 'Team Leadership'],
            ]
        );

        // Create Administrator User
        User::updateOrCreate(
            ['email' => 'administrator@imhotion.com'],
            [
                'name' => 'Administrator User',
                'password' => Hash::make('password123'),
                'role' => 'administrator',
                'is_available' => true,
                'phone' => '+1 (555) 000-0002',
                'address' => '456 Admin Street',
                'city' => 'Admin City',
                'country' => 'US',
                'bio' => 'Project administrator responsible for managing projects and developers.',
                'portfolio_url' => 'https://administrator.imhotion.com',
                'linkedin_url' => 'https://linkedin.com/in/administrator',
                'github_url' => 'https://github.com/administrator',
                'skills' => ['Project Management', 'Team Coordination', 'Client Communication', 'Quality Assurance', 'Process Improvement'],
            ]
        );

        // Create Developer User
        User::updateOrCreate(
            ['email' => 'developer@imhotion.com'],
            [
                'name' => 'Developer User',
                'password' => Hash::make('password123'),
                'role' => 'developer',
                'specialization_id' => $specialization ? $specialization->id : null,
                'experience_level' => 'senior',
                'is_available' => true,
                'phone' => '+1 (555) 000-0003',
                'address' => '789 Developer Street',
                'city' => 'Dev City',
                'country' => 'US',
                'bio' => 'Senior developer with expertise in full-stack development and modern technologies.',
                'portfolio_url' => 'https://developer.imhotion.com',
                'linkedin_url' => 'https://linkedin.com/in/developer',
                'github_url' => 'https://github.com/developer',
                'skills' => ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'MySQL', 'Git', 'Docker', 'AWS', 'REST APIs', 'Agile'],
                'working_hours' => ['Monday' => '9:00-17:00', 'Tuesday' => '9:00-17:00', 'Wednesday' => '9:00-17:00', 'Thursday' => '9:00-17:00', 'Friday' => '9:00-17:00'],
            ]
        );

        // Create Client User
        User::updateOrCreate(
            ['email' => 'client@imhotion.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_available' => false,
                'phone' => '+1 (555) 000-0004',
                'address' => '321 Client Street',
                'city' => 'Client City',
                'country' => 'US',
                'bio' => 'Business client looking for development services.',
                'portfolio_url' => 'https://client.imhotion.com',
                'linkedin_url' => 'https://linkedin.com/in/client',
                'github_url' => 'https://github.com/client',
                'skills' => ['Business Analysis', 'Project Requirements', 'User Experience', 'Quality Assurance', 'Communication'],
            ]
        );

        // Create Additional Developer Users for Testing
        $developers = [
            [
                'name' => 'John Smith',
                'email' => 'john@imhotion.com',
                'role' => 'developer',
                'experience_level' => 'mid',
                'skills' => ['React', 'Node.js', 'MongoDB', 'Express', 'TypeScript', 'GraphQL'],
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@imhotion.com',
                'role' => 'developer',
                'experience_level' => 'junior',
                'skills' => ['HTML', 'CSS', 'JavaScript', 'Bootstrap', 'jQuery', 'PHP'],
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike@imhotion.com',
                'role' => 'developer',
                'experience_level' => 'senior',
                'skills' => ['Python', 'Django', 'PostgreSQL', 'Redis', 'Celery', 'Docker', 'Kubernetes'],
            ],
        ];

        foreach ($developers as $dev) {
            User::updateOrCreate(
                ['email' => $dev['email']],
                [
                    'name' => $dev['name'],
                    'password' => Hash::make('password123'),
                    'role' => $dev['role'],
                    'specialization_id' => $specialization ? $specialization->id : null,
                    'experience_level' => $dev['experience_level'],
                    'is_available' => true,
                    'phone' => '+1 (555) ' . rand(100, 999) . '-' . rand(1000, 9999),
                    'address' => rand(100, 999) . ' Developer Street',
                    'city' => 'Dev City',
                    'country' => 'US',
                    'bio' => 'Experienced developer with strong technical skills and collaborative approach.',
                    'skills' => $dev['skills'],
                    'working_hours' => ['Monday' => '9:00-17:00', 'Tuesday' => '9:00-17:00', 'Wednesday' => '9:00-17:00', 'Thursday' => '9:00-17:00', 'Friday' => '9:00-17:00'],
                ]
            );
        }

        $this->command->info('✅ User roles seeded successfully!');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Admin: admin@imhotion.com / password123');
        $this->command->info('   Administrator: administrator@imhotion.com / password123');
        $this->command->info('   Developer: developer@imhotion.com / password123');
        $this->command->info('   Client: client@imhotion.com / password123');
        $this->command->info('   Additional Developers: john@imhotion.com, sarah@imhotion.com, mike@imhotion.com / password123');
    }
}
