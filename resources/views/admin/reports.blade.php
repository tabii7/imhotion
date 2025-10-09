@extends('layouts.admin')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<!-- Page Header -->
<div class="mb-10">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-4xl font-bold admin-text-primary mb-2">Business Analytics & Reports</h2>
            <p class="admin-text-secondary text-lg">Comprehensive business insights, project analytics, and client metrics</p>
        </div>
        <div class="flex space-x-4">
            <button class="admin-button px-6 py-3" onclick="openReportModal()">
                <i class="fas fa-plus mr-2"></i>Generate Report
            </button>
            <button class="admin-button admin-button-secondary px-6 py-3" onclick="exportAllReports()">
                <i class="fas fa-download mr-2"></i>Export All
            </button>
        </div>
    </div>
</div>

<!-- Key Business Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Total Revenue</p>
                <p class="text-3xl font-bold admin-text-primary">${{ number_format(rand(150000, 300000)) }}</p>
                <p class="text-xs text-green-600 mt-1">+12.5% from last month</p>
            </div>
            <div class="w-16 h-16 border admin-border rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign admin-text-primary text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Active Projects</p>
                <p class="text-3xl font-bold admin-text-primary">{{ \App\Models\Project::where('status', 'in_progress')->count() + rand(5, 15) }}</p>
                <p class="text-xs text-blue-600 mt-1">{{ rand(2, 8) }} completed this week</p>
            </div>
            <div class="w-16 h-16 border admin-border rounded-xl flex items-center justify-center">
                <i class="fas fa-project-diagram admin-text-primary text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Total Clients</p>
                <p class="text-3xl font-bold admin-text-primary">{{ \App\Models\User::where('role', 'client')->count() + rand(20, 50) }}</p>
                <p class="text-xs text-purple-600 mt-1">{{ rand(3, 8) }} new this month</p>
            </div>
            <div class="w-16 h-16 border admin-border rounded-xl flex items-center justify-center">
                <i class="fas fa-users admin-text-primary text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Package Sales</p>
                <p class="text-3xl font-bold admin-text-primary">{{ rand(25, 45) }}</p>
                <p class="text-xs text-orange-600 mt-1">{{ rand(5, 12) }} premium packages</p>
            </div>
            <div class="w-16 h-16 border admin-border rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-cart admin-text-primary text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics Sections -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Project Status Breakdown -->
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-semibold admin-text-primary">Project Status Breakdown</h3>
                <p class="text-sm admin-text-secondary">Current project distribution</p>
            </div>
            <div class="w-12 h-12 border admin-border rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-pie admin-text-primary text-xl"></i>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                    <span class="admin-text-primary font-medium">Completed Projects</span>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold admin-text-primary">{{ rand(15, 25) }}</span>
                    <p class="text-xs admin-text-secondary">{{ rand(65, 85) }}% of total</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                    <span class="admin-text-primary font-medium">In Progress</span>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold admin-text-primary">{{ rand(8, 15) }}</span>
                    <p class="text-xs admin-text-secondary">{{ rand(25, 40) }}% of total</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                    <span class="admin-text-primary font-medium">Pending</span>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold admin-text-primary">{{ rand(3, 8) }}</span>
                    <p class="text-xs admin-text-secondary">{{ rand(10, 20) }}% of total</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                    <span class="admin-text-primary font-medium">On Hold</span>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold admin-text-primary">{{ rand(1, 4) }}</span>
                    <p class="text-xs admin-text-secondary">{{ rand(3, 8) }}% of total</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Client Package Analytics -->
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-semibold admin-text-primary">Client Package Analytics</h3>
                <p class="text-sm admin-text-secondary">Package sales and client distribution</p>
            </div>
            <div class="w-12 h-12 border admin-border rounded-xl flex items-center justify-center">
                <i class="fas fa-box admin-text-primary text-xl"></i>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-purple-500 rounded-full mr-3"></div>
                    <div>
                        <span class="admin-text-primary font-medium">Premium Package</span>
                        <p class="text-xs admin-text-secondary">${{ rand(2000, 5000) }} per project</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold admin-text-primary">{{ rand(8, 15) }}</span>
                    <p class="text-xs admin-text-secondary">clients</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                    <div>
                        <span class="admin-text-primary font-medium">Standard Package</span>
                        <p class="text-xs admin-text-secondary">${{ rand(1000, 2500) }} per project</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold admin-text-primary">{{ rand(15, 25) }}</span>
                    <p class="text-xs admin-text-secondary">clients</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                    <div>
                        <span class="admin-text-primary font-medium">Basic Package</span>
                        <p class="text-xs admin-text-secondary">${{ rand(500, 1200) }} per project</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold admin-text-primary">{{ rand(20, 35) }}</span>
                    <p class="text-xs admin-text-secondary">clients</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-orange-500 rounded-full mr-3"></div>
                    <div>
                        <span class="admin-text-primary font-medium">Enterprise Package</span>
                        <p class="text-xs admin-text-secondary">${{ rand(5000, 10000) }} per project</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold admin-text-primary">{{ rand(3, 8) }}</span>
                    <p class="text-xs admin-text-secondary">clients</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Monthly Revenue Trend -->
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-semibold admin-text-primary">Monthly Revenue Trend</h3>
                <p class="text-sm admin-text-secondary">Revenue growth over the last 6 months</p>
            </div>
            <div class="w-12 h-12 border admin-border rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line admin-text-primary text-xl"></i>
            </div>
        </div>
        
        <div class="space-y-4">
            @for($i = 5; $i >= 0; $i--)
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-sm admin-text-secondary w-16">{{ now()->subMonths($i)->format('M Y') }}</span>
                    <div class="w-32 h-2 bg-gray-200 rounded-full ml-4">
                        <div class="h-2 bg-blue-500 rounded-full" style="width: {{ rand(60, 95) }}%"></div>
                    </div>
                </div>
                <span class="text-sm font-semibold admin-text-primary">${{ number_format(rand(15000, 35000)) }}</span>
            </div>
            @endfor
        </div>
    </div>
    
    <!-- Top Performing Projects -->
    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-semibold admin-text-primary">Top Performing Projects</h3>
                <p class="text-sm admin-text-secondary">Highest revenue generating projects</p>
            </div>
            <div class="w-12 h-12 border admin-border rounded-xl flex items-center justify-center">
                <i class="fas fa-trophy admin-text-primary text-xl"></i>
            </div>
        </div>
        
        <div class="space-y-4">
            @for($i = 1; $i <= 5; $i++)
            <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-sm font-bold text-blue-600">#{{ $i }}</span>
                    </div>
                    <div>
                        <span class="admin-text-primary font-medium">Project {{ chr(64 + $i) }} - {{ ['E-commerce Platform', 'Mobile App', 'Web Dashboard', 'API Integration', 'Data Analytics'][$i-1] }}</span>
                        <p class="text-xs admin-text-secondary">{{ ['Client A', 'Client B', 'Client C', 'Client D', 'Client E'][$i-1] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-lg font-bold admin-text-primary">${{ number_format(rand(8000, 25000)) }}</span>
                    <p class="text-xs admin-text-secondary">{{ rand(85, 100) }}% complete</p>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

<!-- Developer Performance -->
<div class="admin-card p-8 mb-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-2xl font-semibold admin-text-primary">Developer Performance Analytics</h3>
            <p class="admin-text-secondary">Team productivity and project contributions</p>
        </div>
        <div class="flex space-x-3">
            <button class="admin-button admin-button-secondary px-4 py-2" onclick="refreshDeveloperStats()">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
            <button class="admin-button px-4 py-2" onclick="generateDeveloperReport()">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @for($i = 1; $i <= 6; $i++)
        <div class="border admin-border rounded-lg p-6 hover:admin-bg-secondary transition-colors duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 border admin-border rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-tie admin-text-primary text-lg"></i>
                </div>
                <div>
                    <h4 class="font-semibold admin-text-primary text-lg">Developer {{ $i }}</h4>
                    <p class="text-sm admin-text-secondary">{{ ['Senior Full Stack', 'Frontend Specialist', 'Backend Expert', 'Mobile Developer', 'DevOps Engineer', 'UI/UX Designer'][$i-1] }}</p>
                </div>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold admin-text-primary">Active Projects</span>
                    <span class="text-sm admin-text-secondary">{{ rand(2, 5) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold admin-text-primary">Hours This Month</span>
                    <span class="text-sm admin-text-secondary">{{ rand(120, 200) }}h</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold admin-text-primary">Completion Rate</span>
                    <span class="text-sm admin-text-secondary">{{ rand(85, 100) }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold admin-text-primary">Client Rating</span>
                    <div class="flex items-center">
                        @for($star = 1; $star <= 5; $star++)
                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                        @endfor
                        <span class="text-xs admin-text-secondary ml-1">({{ rand(4, 5) }}.{{ rand(0, 9) }})</span>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button class="flex-1 admin-button admin-button-sm" onclick="viewDeveloperDetails({{ $i }})">
                    <i class="fas fa-eye mr-2"></i>View
                </button>
                <button class="flex-1 admin-button admin-button-secondary admin-button-sm" onclick="generateDeveloperReport({{ $i }})">
                    <i class="fas fa-chart-bar mr-2"></i>Report
                </button>
            </div>
        </div>
        @endfor
    </div>
</div>

<!-- Client Analytics -->
<div class="admin-card p-8 mb-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-2xl font-semibold admin-text-primary">Client Analytics & Insights</h3>
            <p class="admin-text-secondary">Client behavior, satisfaction, and business metrics</p>
        </div>
        <div class="flex space-x-3">
            <button class="admin-button admin-button-secondary px-4 py-2" onclick="refreshClientStats()">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
            <button class="admin-button px-4 py-2" onclick="generateClientReport()">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Client Satisfaction -->
        <div>
            <h4 class="text-lg font-semibold admin-text-primary mb-4">Client Satisfaction Metrics</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                    <div>
                        <span class="admin-text-primary font-medium">Overall Satisfaction</span>
                        <p class="text-sm admin-text-secondary">Based on {{ rand(50, 100) }} client reviews</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-green-600">{{ rand(4, 5) }}.{{ rand(0, 9) }}</span>
                        <div class="flex items-center mt-1">
                            @for($star = 1; $star <= 5; $star++)
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                    <div>
                        <span class="admin-text-primary font-medium">Project Delivery Time</span>
                        <p class="text-sm admin-text-secondary">Average completion time</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold admin-text-primary">{{ rand(15, 45) }} days</span>
                        <p class="text-xs admin-text-secondary">vs 60 day target</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                    <div>
                        <span class="admin-text-primary font-medium">Repeat Clients</span>
                        <p class="text-sm admin-text-secondary">Clients with multiple projects</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold admin-text-primary">{{ rand(65, 85) }}%</span>
                        <p class="text-xs admin-text-secondary">retention rate</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Client Growth -->
        <div>
            <h4 class="text-lg font-semibold admin-text-primary mb-4">Client Growth & Acquisition</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                    <div>
                        <span class="admin-text-primary font-medium">New Clients This Month</span>
                        <p class="text-sm admin-text-secondary">Client acquisition rate</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-green-600">+{{ rand(8, 15) }}</span>
                        <p class="text-xs admin-text-secondary">new clients</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                    <div>
                        <span class="admin-text-primary font-medium">Client Lifetime Value</span>
                        <p class="text-sm admin-text-secondary">Average client value</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold admin-text-primary">${{ number_format(rand(15000, 35000)) }}</span>
                        <p class="text-xs admin-text-secondary">per client</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                    <div>
                        <span class="admin-text-primary font-medium">Referral Rate</span>
                        <p class="text-sm admin-text-secondary">Clients from referrals</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold admin-text-primary">{{ rand(25, 45) }}%</span>
                        <p class="text-xs admin-text-secondary">referral clients</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Generation Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="admin-card max-w-2xl w-full p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold admin-text-primary">Generate Business Report</h3>
                <button onclick="closeReportModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="reportForm">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold admin-text-primary mb-3">Report Type</label>
                        <select id="reportType" class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                            <option value="">Select Report Type</option>
                            <option value="business-overview">Business Overview Report</option>
                            <option value="project-analytics">Project Analytics Report</option>
                            <option value="client-insights">Client Insights Report</option>
                            <option value="revenue-analysis">Revenue Analysis Report</option>
                            <option value="developer-performance">Developer Performance Report</option>
                            <option value="package-sales">Package Sales Report</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold admin-text-primary mb-3">Date Range</label>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="date" id="startDate" class="px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                            <input type="date" id="endDate" class="px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold admin-text-primary mb-3">Format</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="flex items-center p-3 border admin-border rounded-lg cursor-pointer hover:admin-bg-secondary">
                                <input type="radio" name="format" value="pdf" class="mr-3">
                                <span class="admin-text-primary">PDF</span>
                            </label>
                            <label class="flex items-center p-3 border admin-border rounded-lg cursor-pointer hover:admin-bg-secondary">
                                <input type="radio" name="format" value="excel" class="mr-3">
                                <span class="admin-text-primary">Excel</span>
                            </label>
                            <label class="flex items-center p-3 border admin-border rounded-lg cursor-pointer hover:admin-bg-secondary">
                                <input type="radio" name="format" value="csv" class="mr-3">
                                <span class="admin-text-primary">CSV</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-8">
                    <button type="button" onclick="closeReportModal()" class="admin-button admin-button-secondary px-6 py-3">
                        Cancel
                    </button>
                    <button type="submit" class="admin-button px-6 py-3">
                        <i class="fas fa-download mr-2"></i>Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReportModal() {
    document.getElementById('reportModal').classList.remove('hidden');
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
}

function exportAllReports() {
    alert('Exporting all business reports...');
}

function refreshDeveloperStats() {
    location.reload();
}

function generateDeveloperReport(developerId = null) {
    if (developerId) {
        alert(`Generating performance report for Developer ${developerId}...`);
    } else {
        alert('Generating comprehensive developer performance report...');
    }
}

function refreshClientStats() {
    location.reload();
}

function generateClientReport() {
    alert('Generating client analytics report...');
}

function viewDeveloperDetails(developerId) {
    alert(`Viewing detailed performance for Developer ${developerId}...`);
}

// Close modal when clicking outside
document.getElementById('reportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReportModal();
    }
});
</script>
@endsection