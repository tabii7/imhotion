@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Client Dashboard -->
<div class="rounded-xl p-5 text-white">
    <div class="text-white font-sans">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-sidebar-text">Manage your projects, track progress, and stay updated with your development work.</p>
        </div>

        <!-- Enhanced Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mx-auto mb-8 max-w-4xl">
            <div class="bg-sidebar-active border border-blue-300 rounded-xl px-4 py-4 text-center min-h-[80px] flex flex-col justify-center transition-all duration-200 cursor-pointer hover:scale-105 hover:bg-blue-900/50">
                <div class="text-white text-xs font-medium mb-2 tracking-wide">
                    Active Projects
                </div>
                <div class="text-white text-lg font-semibold px-3 py-1 border border-blue-300 rounded-xl inline-block min-w-[40px] transition-colors duration-200 bg-blue-900/50 hover:bg-blue-800/50">
                    {{ $counts['active'] ?? 0 }}
                </div>
            </div>
            
            <div class="bg-sidebar-active border border-green-300 rounded-xl px-4 py-4 text-center min-h-[80px] flex flex-col justify-center transition-all duration-200 cursor-pointer hover:scale-105 hover:bg-green-900/50">
                <div class="text-white text-xs font-medium mb-2 tracking-wide">
                    Hours Purchased
                </div>
                <div class="text-white text-lg font-semibold px-3 py-1 border border-green-300 rounded-xl inline-block min-w-[40px] transition-colors duration-200 bg-green-900/50 hover:bg-green-800/50">
                    {{ number_format($counts['total_hours_purchased'] ?? 0, 1) }}h
                </div>
            </div>

            <div class="bg-sidebar-active border border-yellow-300 rounded-xl px-4 py-4 text-center min-h-[80px] flex flex-col justify-center transition-all duration-200 cursor-pointer hover:scale-105 hover:bg-yellow-900/50">
                <div class="text-white text-xs font-medium mb-2 tracking-wide">
                    Hours Used
                </div>
                <div class="text-white text-lg font-semibold px-3 py-1 border border-yellow-300 rounded-xl inline-block min-w-[40px] transition-colors duration-200 bg-yellow-900/50 hover:bg-yellow-800/50">
                    {{ number_format($counts['total_hours_used'] ?? 0, 1) }}h
                </div>
            </div>

            <div class="bg-sidebar-active border border-purple-300 rounded-xl px-4 py-4 text-center min-h-[80px] flex flex-col justify-center transition-all duration-200 cursor-pointer hover:scale-105 hover:bg-purple-900/50">
                <div class="text-white text-xs font-medium mb-2 tracking-wide">
                    Hours Remaining
                </div>
                <div class="text-white text-lg font-semibold px-3 py-1 border border-purple-300 rounded-xl inline-block min-w-[40px] transition-colors duration-200 bg-purple-900/50 hover:bg-purple-800/50">
                    {{ number_format($counts['total_hours_remaining'] ?? 0, 1) }}h
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-sidebar-active border border-gray-300 rounded-xl p-6 mb-8">
            <h3 class="text-white text-lg font-semibold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button onclick="openBuyHoursModal()" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Buy Hours
                </button>
                <button onclick="openAddProjectModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Project
                </button>
                <button onclick="openReportsModal()" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    View Reports
                </button>
            </div>
        </div>

        <!-- Hours Usage Chart -->
        <div class="bg-sidebar-active border border-gray-300 rounded-xl p-6 mb-8">
            <h3 class="text-white text-lg font-semibold mb-4">Hours Usage Overview</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-300 text-sm">Hours Used</span>
                        <span class="text-blue-400 font-semibold">{{ number_format($counts['total_hours_used'] ?? 0, 1) }}h</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ ($counts['total_hours_purchased'] ?? 0) > 0 ? (($counts['total_hours_used'] ?? 0) / ($counts['total_hours_purchased'] ?? 1)) * 100 : 0 }}%"></div>
                    </div>
                    <p class="text-gray-400 text-xs mt-1">
                        {{ ($counts['total_hours_purchased'] ?? 0) > 0 ? number_format((($counts['total_hours_used'] ?? 0) / ($counts['total_hours_purchased'] ?? 1)) * 100, 1) : 0 }}% of purchased hours used
                    </p>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-300 text-sm">Hours Remaining</span>
                        <span class="text-yellow-400 font-semibold">{{ number_format($counts['total_hours_remaining'] ?? 0, 1) }}h</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ ($counts['total_hours_purchased'] ?? 0) > 0 ? (($counts['total_hours_remaining'] ?? 0) / ($counts['total_hours_purchased'] ?? 1)) * 100 : 0 }}%"></div>
                    </div>
                    <p class="text-gray-400 text-xs mt-1">
                        {{ ($counts['total_hours_remaining'] ?? 0) > 0 ? 'Hours available for new projects' : 'All hours have been used' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Recent Updates & Notifications -->
        <div class="bg-sidebar-active border border-gray-300 rounded-xl p-6 mb-8">
            <h3 class="text-white text-lg font-semibold mb-4">Recent Updates & Notifications</h3>
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-blue-900/30 border border-blue-300/30 rounded-lg">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium">Project "E-commerce Platform" - 75% Complete</p>
                        <p class="text-gray-400 text-xs">Updated 2 hours ago</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-green-900/30 border border-green-300/30 rounded-lg">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-upload text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium">New files uploaded to "Mobile App" project</p>
                        <p class="text-gray-400 text-xs">Updated 4 hours ago</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-yellow-900/30 border border-yellow-300/30 rounded-lg">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium">Weekly progress report available</p>
                        <p class="text-gray-400 text-xs">Updated 1 day ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Projects Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-white text-xl font-semibold tracking-wide">
                    Active Projects
                </h2>
                <button onclick="openAddProjectModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add Project
                </button>
            </div>

            <div class="space-y-3">
                @forelse($active ?? [] as $project)
                    @include('dashboard.project-item', ['project' => $project, 'section' => 'active'])
                @empty
                    <div class="text-center py-8">
                        <div class="text-sidebar-text text-lg mb-4">No active projects yet</div>
                        <button onclick="openAddProjectModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Create Your First Project
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Finalized Projects Section -->
        <div class="mb-8">
            <h2 class="text-white text-xl font-semibold mb-6 tracking-wide">
                Completed Projects
            </h2>

            <div class="space-y-3">
                @forelse($finalized ?? [] as $project)
                    @include('dashboard.project-item', ['project' => $project, 'section' => 'finalized'])
                @empty
                    <div class="text-center py-8">
                        <div class="text-sidebar-text text-lg mb-2">No completed projects yet</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Buy Hours Modal -->
<div id="buyHoursModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Purchase Development Hours</h3>
                <button onclick="closeBuyHoursModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div class="space-y-6">
                <!-- Hour Packages -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Choose Your Package</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-lg transition-all cursor-pointer" onclick="selectPackage('basic')">
                            <div class="text-center">
                                <h5 class="font-semibold text-gray-900">Basic Package</h5>
                                <div class="text-2xl font-bold text-blue-600 mt-2">20 Hours</div>
                                <div class="text-gray-600">$1,200</div>
                                <div class="text-sm text-gray-500 mt-1">$60/hour</div>
                            </div>
                        </div>
                        
                        <div class="border border-blue-500 rounded-lg p-4 bg-blue-50 hover:shadow-lg transition-all cursor-pointer" onclick="selectPackage('standard')">
                            <div class="text-center">
                                <h5 class="font-semibold text-gray-900">Standard Package</h5>
                                <div class="text-2xl font-bold text-blue-600 mt-2">50 Hours</div>
                                <div class="text-gray-600">$2,500</div>
                                <div class="text-sm text-gray-500 mt-1">$50/hour</div>
                                <div class="text-xs text-green-600 mt-1">Save $500</div>
                            </div>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-lg transition-all cursor-pointer" onclick="selectPackage('premium')">
                            <div class="text-center">
                                <h5 class="font-semibold text-gray-900">Premium Package</h5>
                                <div class="text-2xl font-bold text-blue-600 mt-2">100 Hours</div>
                                <div class="text-gray-600">$4,000</div>
                                <div class="text-sm text-gray-500 mt-1">$40/hour</div>
                                <div class="text-xs text-green-600 mt-1">Save $2,000</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h4>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-500">
                            <input type="radio" name="payment_method" value="stripe" class="mr-3">
                            <div class="flex items-center">
                                <i class="fab fa-cc-stripe text-blue-600 mr-2"></i>
                                <span>Credit/Debit Card</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-500">
                            <input type="radio" name="payment_method" value="paypal" class="mr-3">
                            <div class="flex items-center">
                                <i class="fab fa-paypal text-blue-600 mr-2"></i>
                                <span>PayPal</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
                <button onclick="closeBuyHoursModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button onclick="proceedToPayment()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-credit-card mr-2"></i>
                    Proceed to Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Project Modal -->
<div id="addProjectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Add New Project</h3>
                <button onclick="closeAddProjectModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <form id="addProjectForm" class="space-y-6">
                <div>
                    <label for="project_title" class="block text-sm font-medium text-gray-700 mb-2">Project Title</label>
                    <input type="text" id="project_title" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="project_topic" class="block text-sm font-medium text-gray-700 mb-2">Project Topic/Description</label>
                    <textarea id="project_topic" name="topic" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">Estimated Hours</label>
                        <input type="number" id="estimated_hours" name="estimated_hours" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select id="priority" name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="project_notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea id="project_notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </form>
            
            <div class="flex justify-end space-x-4 mt-8">
                <button onclick="closeAddProjectModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button onclick="submitNewProject()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Create Project
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reports Modal -->
<div id="reportsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Project Reports & Analytics</h3>
                <button onclick="closeReportsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Project Summary</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Projects:</span>
                            <span class="font-semibold">{{ ($counts['active'] ?? 0) + count($finalized ?? []) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Projects:</span>
                            <span class="font-semibold text-blue-600">{{ $counts['active'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Completed Projects:</span>
                            <span class="font-semibold text-green-600">{{ count($finalized ?? []) }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Hours Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Hours Summary</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Hours Purchased:</span>
                            <span class="font-semibold text-green-600">{{ number_format($counts['total_hours_purchased'] ?? 0, 1) }}h</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Hours Used:</span>
                            <span class="font-semibold text-yellow-600">{{ number_format($counts['total_hours_used'] ?? 0, 1) }}h</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Hours Remaining:</span>
                            <span class="font-semibold text-purple-600">{{ number_format($counts['total_hours_remaining'] ?? 0, 1) }}h</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <h4 class="font-semibold text-gray-900 mb-3">Export Options</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button onclick="exportReport('pdf')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export as PDF
                    </button>
                    <button onclick="exportReport('excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export as Excel
                    </button>
                    <button onclick="exportReport('csv')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-file-csv mr-2"></i>
                        Export as CSV
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Modal -->
<div id="projectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Project Details</h3>
                <button onclick="closeProjectModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div id="projectModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- File Lightbox -->
<div id="fileLightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-[90vh] w-full">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div id="lightboxContent" class="bg-white rounded-lg overflow-hidden">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Modal Functions
function openBuyHoursModal() {
    document.getElementById('buyHoursModal').classList.remove('hidden');
    document.getElementById('buyHoursModal').classList.add('flex');
}

function closeBuyHoursModal() {
    document.getElementById('buyHoursModal').classList.add('hidden');
    document.getElementById('buyHoursModal').classList.remove('flex');
}

function openAddProjectModal() {
    document.getElementById('addProjectModal').classList.remove('hidden');
    document.getElementById('addProjectModal').classList.add('flex');
}

function closeAddProjectModal() {
    document.getElementById('addProjectModal').classList.add('hidden');
    document.getElementById('addProjectModal').classList.remove('flex');
}

function openReportsModal() {
    document.getElementById('reportsModal').classList.remove('hidden');
    document.getElementById('reportsModal').classList.add('flex');
}

function closeReportsModal() {
    document.getElementById('reportsModal').classList.add('hidden');
    document.getElementById('reportsModal').classList.remove('flex');
}

// Package Selection
function selectPackage(package) {
    // Remove previous selections
    document.querySelectorAll('.border-blue-500').forEach(el => {
        el.classList.remove('border-blue-500', 'bg-blue-50');
        el.classList.add('border-gray-200');
    });
    
    // Add selection to clicked package
    event.currentTarget.classList.remove('border-gray-200');
    event.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
}

// Payment Processing
function proceedToPayment() {
    const selectedPackage = document.querySelector('.border-blue-500');
    if (!selectedPackage) {
        alert('Please select a package');
        return;
    }
    
    alert('Redirecting to payment gateway...');
    // Here you would integrate with Stripe/PayPal
}

// Project Management
function submitNewProject() {
    const form = document.getElementById('addProjectForm');
    const formData = new FormData(form);
    
    // Validate form
    if (!formData.get('title') || !formData.get('topic') || !formData.get('estimated_hours')) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Submit project (simulate)
    alert('Project created successfully!');
    closeAddProjectModal();
    location.reload();
}

// Report Export
function exportReport(format) {
    alert(`Exporting report as ${format.toUpperCase()}...`);
    closeReportsModal();
}

// Existing project modal functions
function openProjectModal(projectId) {
    const projectElement = document.querySelector(`[data-project-id="${projectId}"]`);
    if (!projectElement) {
        console.error('Project element not found');
        return;
    }

    const projectData = {
        id: projectId,
        title: projectElement.dataset.projectTitle,
        topic: projectElement.dataset.projectTopic,
        status: projectElement.dataset.projectStatus,
        delivery_date: projectElement.dataset.projectDeliveryDate,
        days_used: projectElement.dataset.projectDaysUsed,
        day_budget: projectElement.dataset.projectDayBudget,
        notes: projectElement.dataset.projectNotes,
        start_date: projectElement.dataset.projectStartDate,
        end_date: projectElement.dataset.projectEndDate,
        progress: projectElement.dataset.projectProgress
    };

    document.getElementById('projectModalContent').innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
                    <div class="text-gray-900 font-medium">${projectData.title || 'N/A'}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                    <div class="text-gray-900">${projectData.topic || 'No topic specified'}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(projectData.status)}">
                            ${getStatusLabel(projectData.status)}
                        </span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Progress</label>
                    <div class="text-gray-900">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: ${projectData.progress || 0}%"></div>
                        </div>
                        <span class="text-sm text-gray-600">${projectData.progress || 0}%</span>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <div class="text-gray-900">${projectData.start_date ? new Date(projectData.start_date).toLocaleDateString() : 'Not set'}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <div class="text-gray-900">${projectData.end_date ? new Date(projectData.end_date).toLocaleDateString() : 'Not set'}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                    <div class="text-gray-900">${projectData.delivery_date ? new Date(projectData.delivery_date).toLocaleDateString() : 'TBD'}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Days Used</label>
                    <div class="text-gray-900">${projectData.days_used || 0} days</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day Budget</label>
                    <div class="text-gray-900">${projectData.day_budget ? '€' + projectData.day_budget : 'Not set'}</div>
                </div>
            </div>
        </div>
        
        ${projectData.notes ? `
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">${projectData.notes}</div>
            </div>
        ` : ''}
    `;
    
    document.getElementById('projectModal').classList.remove('hidden');
    document.getElementById('projectModal').classList.add('flex');
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Pending',
        'in_progress': 'In Progress',
        'completed': 'Completed',
        'on_hold': 'On Hold',
        'finalized': 'Finalized',
        'cancelled': 'Cancelled'
    };
    return labels[status] || status;
}

function getStatusColor(status) {
    const colors = {
        'pending': 'bg-gray-100 text-gray-800',
        'in_progress': 'bg-yellow-100 text-yellow-800',
        'completed': 'bg-green-100 text-green-800',
        'on_hold': 'bg-orange-100 text-orange-800',
        'finalized': 'bg-blue-100 text-blue-800',
        'cancelled': 'bg-red-100 text-red-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function closeProjectModal() {
    document.getElementById('projectModal').classList.add('hidden');
    document.getElementById('projectModal').classList.remove('flex');
}

function downloadFiles(projectId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/projects/${projectId}/download`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    form.appendChild(csrfToken);
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function markAsFinalized(projectId) {
    if (confirm('Are you sure you want to mark this project as finalized?')) {
        fetch(`/api/projects/${projectId}/finalize`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error finalizing project: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error finalizing project');
        });
    }
}

function openLightbox(fileUrl, fileName) {
    const extension = fileName.split('.').pop().toLowerCase();
    let content = '';
    
    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
        content = `<img src="${fileUrl}" alt="${fileName}" class="w-full h-auto max-h-[80vh] object-contain">`;
    } else if (['mp4', 'webm', 'ogg'].includes(extension)) {
        content = `<video controls class="w-full h-auto max-h-[80vh]"><source src="${fileUrl}" type="video/${extension}"></video>`;
    } else if (['pdf'].includes(extension)) {
        content = `<iframe src="${fileUrl}" class="w-full h-[80vh] border-0"></iframe>`;
    } else {
        content = `<div class="p-8 text-center"><p class="text-gray-600 mb-4">Preview not available for this file type.</p><a href="${fileUrl}" download="${fileName}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Download ${fileName}</a></div>`;
    }
    
    document.getElementById('lightboxContent').innerHTML = content;
    document.getElementById('fileLightbox').classList.remove('hidden');
    document.getElementById('fileLightbox').classList.add('flex');
}

function closeLightbox() {
    document.getElementById('fileLightbox').classList.add('hidden');
    document.getElementById('fileLightbox').classList.remove('flex');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.id === 'projectModal') {
        closeProjectModal();
    }
    if (event.target.id === 'fileLightbox') {
        closeLightbox();
    }
    if (event.target.id === 'buyHoursModal') {
        closeBuyHoursModal();
    }
    if (event.target.id === 'addProjectModal') {
        closeAddProjectModal();
    }
    if (event.target.id === 'reportsModal') {
        closeReportsModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        if (!document.getElementById('fileLightbox').classList.contains('hidden')) {
            closeLightbox();
        } else if (!document.getElementById('projectModal').classList.contains('hidden')) {
            closeProjectModal();
        } else if (!document.getElementById('buyHoursModal').classList.contains('hidden')) {
            closeBuyHoursModal();
        } else if (!document.getElementById('addProjectModal').classList.contains('hidden')) {
            closeAddProjectModal();
        } else if (!document.getElementById('reportsModal').classList.contains('hidden')) {
            closeReportsModal();
        }
    }
});
</script>
@endsection