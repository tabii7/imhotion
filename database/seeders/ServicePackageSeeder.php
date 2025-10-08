<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServicePackage;

class ServicePackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'UI/UX Design - Hourly',
                'slug' => 'ui-ux-design-hourly',
                'description' => 'Professional UI/UX design services by the hour',
                'service_type' => 'ui_ux_design',
                'time_unit' => 'hour',
                'price_per_unit' => 75.00,
                'min_units' => 1,
                'max_units' => 40,
                'features' => [
                    'User research and analysis',
                    'Wireframing and prototyping',
                    'Visual design and branding',
                    'User testing and feedback',
                    'Design system creation',
                    'Responsive design'
                ],
                'specializations' => [1, 2], // Assuming UI/UX specializations
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Web Development - Daily',
                'slug' => 'web-development-daily',
                'description' => 'Full-stack web development services by the day',
                'service_type' => 'web_development',
                'time_unit' => 'day',
                'price_per_unit' => 600.00,
                'min_units' => 1,
                'max_units' => 30,
                'features' => [
                    'Frontend development (React, Vue, Angular)',
                    'Backend development (Laravel, Node.js)',
                    'Database design and implementation',
                    'API development and integration',
                    'Testing and quality assurance',
                    'Deployment and maintenance'
                ],
                'specializations' => [3, 4], // Assuming web development specializations
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Backend Development - Weekly',
                'slug' => 'backend-development-weekly',
                'description' => 'Backend and database development services by the week',
                'service_type' => 'backend_development',
                'time_unit' => 'week',
                'price_per_unit' => 3000.00,
                'min_units' => 1,
                'max_units' => 12,
                'features' => [
                    'API design and development',
                    'Database architecture and optimization',
                    'Server configuration and deployment',
                    'Security implementation',
                    'Performance optimization',
                    'Documentation and testing'
                ],
                'specializations' => [5, 6], // Assuming backend specializations
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Mobile App Development - Weekly',
                'slug' => 'mobile-development-weekly',
                'description' => 'Native and cross-platform mobile app development',
                'service_type' => 'mobile_development',
                'time_unit' => 'week',
                'price_per_unit' => 3500.00,
                'min_units' => 1,
                'max_units' => 16,
                'features' => [
                    'iOS and Android development',
                    'Cross-platform solutions (React Native, Flutter)',
                    'App store optimization',
                    'Push notifications and analytics',
                    'Backend integration',
                    'Testing and deployment'
                ],
                'specializations' => [7, 8], // Assuming mobile development specializations
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Database Development - Daily',
                'slug' => 'database-development-daily',
                'description' => 'Database design, optimization, and management services',
                'service_type' => 'database_development',
                'time_unit' => 'day',
                'price_per_unit' => 500.00,
                'min_units' => 1,
                'max_units' => 20,
                'features' => [
                    'Database design and architecture',
                    'Performance optimization',
                    'Data migration and cleanup',
                    'Security implementation',
                    'Backup and recovery setup',
                    'Monitoring and maintenance'
                ],
                'specializations' => [9, 10], // Assuming database specializations
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Full Stack Development - Weekly',
                'slug' => 'full-stack-development-weekly',
                'description' => 'Complete full-stack development solutions',
                'service_type' => 'full_stack',
                'time_unit' => 'week',
                'price_per_unit' => 4000.00,
                'min_units' => 1,
                'max_units' => 20,
                'features' => [
                    'Frontend and backend development',
                    'Database design and implementation',
                    'API development and integration',
                    'DevOps and deployment',
                    'Testing and quality assurance',
                    'Project management and documentation'
                ],
                'specializations' => [3, 4, 5, 6], // Multiple specializations
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Technical Consulting - Hourly',
                'slug' => 'technical-consulting-hourly',
                'description' => 'Expert technical consulting and advisory services',
                'service_type' => 'consulting',
                'time_unit' => 'hour',
                'price_per_unit' => 100.00,
                'min_units' => 1,
                'max_units' => 50,
                'features' => [
                    'Technical architecture review',
                    'Code review and optimization',
                    'Technology stack recommendations',
                    'Performance analysis',
                    'Security audit',
                    'Project planning and strategy'
                ],
                'specializations' => [], // All specializations
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($packages as $package) {
            ServicePackage::create($package);
        }
    }
}