@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">Pricing Management</h2>
            <p class="admin-text-secondary mt-2">Manage subscription plans and pricing tiers</p>
        </div>
        <div class="flex space-x-3">
            <button class="admin-button">
                <i class="fas fa-plus mr-2"></i>New Plan
            </button>
            <button class="admin-button admin-button-secondary">
                <i class="fas fa-chart-line mr-2"></i>Analytics
            </button>
        </div>
    </div>
</div>

<!-- Pricing Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-blue-600"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium admin-text-secondary">Total Revenue</p>
                <p class="text-2xl font-semibold admin-text-primary">$12,450</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-users text-green-600"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium admin-text-secondary">Active Subscriptions</p>
                <p class="text-2xl font-semibold admin-text-primary">156</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium admin-text-secondary">Monthly Growth</p>
                <p class="text-2xl font-semibold admin-text-primary">+12.5%</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-percentage text-orange-600"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium admin-text-secondary">Conversion Rate</p>
                <p class="text-2xl font-semibold admin-text-primary">8.2%</p>
            </div>
        </div>
    </div>
</div>

<!-- Pricing Plans -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Basic Plan -->
    <div class="admin-card">
        <div class="px-6 py-4 border-b admin-border">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold admin-text-primary">Basic Plan</h3>
                <span class="admin-badge bg-gradient-to-r from-gray-500 to-gray-600">Popular</span>
            </div>
            <p class="text-sm admin-text-secondary mt-1">Perfect for small teams</p>
        </div>
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="text-4xl font-bold admin-text-primary">$29</div>
                <div class="text-sm admin-text-secondary">per month</div>
            </div>
            <ul class="space-y-3 mb-6">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Up to 5 team members</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Basic project management</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Email support</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">5GB storage</span>
                </li>
            </ul>
            <div class="flex space-x-2">
                <button class="flex-1 admin-button admin-button-secondary">Edit</button>
                <button class="flex-1 admin-button">View Details</button>
            </div>
        </div>
    </div>

    <!-- Professional Plan -->
    <div class="admin-card border-2 border-blue-500 relative">
        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium">Most Popular</span>
        </div>
        <div class="px-6 py-4 border-b admin-border">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold admin-text-primary">Professional Plan</h3>
                <span class="admin-badge bg-gradient-to-r from-blue-500 to-blue-600">Recommended</span>
            </div>
            <p class="text-sm admin-text-secondary mt-1">Best for growing businesses</p>
        </div>
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="text-4xl font-bold admin-text-primary">$79</div>
                <div class="text-sm admin-text-secondary">per month</div>
            </div>
            <ul class="space-y-3 mb-6">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Up to 25 team members</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Advanced project management</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Priority support</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">50GB storage</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Analytics dashboard</span>
                </li>
            </ul>
            <div class="flex space-x-2">
                <button class="flex-1 admin-button admin-button-secondary">Edit</button>
                <button class="flex-1 admin-button">View Details</button>
            </div>
        </div>
    </div>

    <!-- Enterprise Plan -->
    <div class="admin-card">
        <div class="px-6 py-4 border-b admin-border">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold admin-text-primary">Enterprise Plan</h3>
                <span class="admin-badge bg-gradient-to-r from-purple-500 to-purple-600">Custom</span>
            </div>
            <p class="text-sm admin-text-secondary mt-1">For large organizations</p>
        </div>
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="text-4xl font-bold admin-text-primary">$199</div>
                <div class="text-sm admin-text-secondary">per month</div>
            </div>
            <ul class="space-y-3 mb-6">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Unlimited team members</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Full project management suite</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">24/7 dedicated support</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Unlimited storage</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-sm admin-text-primary">Custom integrations</span>
                </li>
            </ul>
            <div class="flex space-x-2">
                <button class="flex-1 admin-button admin-button-secondary">Edit</button>
                <button class="flex-1 admin-button">View Details</button>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
<div class="admin-card mb-8">
    <div class="px-6 py-4 border-b admin-border">
        <h3 class="text-lg font-semibold admin-text-primary">Revenue Overview</h3>
        <p class="text-sm admin-text-secondary">Monthly revenue trends and subscription growth</p>
    </div>
    <div class="p-6">
        <div class="h-64 flex items-center justify-center admin-bg-secondary rounded-lg">
            <div class="text-center">
                <i class="fas fa-chart-line text-4xl admin-text-secondary mb-4"></i>
                <p class="admin-text-secondary">Revenue chart will be implemented here</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Subscriptions -->
<div class="admin-card">
    <div class="px-6 py-4 border-b admin-border">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold admin-text-primary">Recent Subscriptions</h3>
            <button class="admin-button admin-button-secondary text-sm">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="admin-bg-secondary">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y admin-border">
                <tr class="hover:admin-bg-secondary transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">JD</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium admin-text-primary">John Doe</div>
                                <div class="text-sm admin-text-secondary">john@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="admin-badge bg-gradient-to-r from-blue-500 to-blue-600">Professional</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-primary">$79.00</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="admin-badge bg-gradient-to-r from-green-500 to-green-600">Active</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">Dec 15, 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-yellow-600 hover:text-yellow-900 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:admin-bg-secondary transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">JS</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium admin-text-primary">Jane Smith</div>
                                <div class="text-sm admin-text-secondary">jane@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="admin-badge bg-gradient-to-r from-gray-500 to-gray-600">Basic</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-primary">$29.00</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="admin-badge bg-gradient-to-r from-green-500 to-green-600">Active</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">Dec 14, 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-yellow-600 hover:text-yellow-900 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
