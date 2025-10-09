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
            <h2 class="text-4xl font-bold admin-text-primary mb-2">System Settings</h2>
            <p class="admin-text-secondary text-lg">Configure system-wide settings and preferences</p>
        </div>
        <div class="flex space-x-4">
            <button class="admin-button px-6 py-3" onclick="saveAllSettings()">
                <i class="fas fa-save mr-2"></i>Save All Changes
            </button>
            <button class="admin-button admin-button-secondary px-6 py-3" onclick="resetToDefaults()">
                <i class="fas fa-undo mr-2"></i>Reset to Default
            </button>
        </div>
    </div>
</div>

<!-- Settings Tabs -->
<div class="admin-card mb-8" x-data="{ activeTab: 'general' }">
    <div class="border-b admin-border">
        <nav class="-mb-px flex space-x-8 px-6">
            <button @click="activeTab = 'general'" 
                    :class="activeTab === 'general' ? 'border-blue-500 admin-text-primary' : 'border-transparent admin-text-secondary hover:admin-text-primary hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-cog mr-2"></i>General
            </button>
            <button @click="activeTab = 'email'" 
                    :class="activeTab === 'email' ? 'border-blue-500 admin-text-primary' : 'border-transparent admin-text-secondary hover:admin-text-primary hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-envelope mr-2"></i>Email
            </button>
            <button @click="activeTab = 'security'" 
                    :class="activeTab === 'security' ? 'border-blue-500 admin-text-primary' : 'border-transparent admin-text-secondary hover:admin-text-primary hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-shield-alt mr-2"></i>Security
            </button>
            <button @click="activeTab = 'notifications'" 
                    :class="activeTab === 'notifications' ? 'border-blue-500 admin-text-primary' : 'border-transparent admin-text-secondary hover:admin-text-primary hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-bell mr-2"></i>Notifications
            </button>
            <button @click="activeTab = 'integrations'" 
                    :class="activeTab === 'integrations' ? 'border-blue-500 admin-text-primary' : 'border-transparent admin-text-secondary hover:admin-text-primary hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-plug mr-2"></i>Integrations
            </button>
        </nav>
    </div>
    
    <!-- General Settings Tab -->
    <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="px-6 py-4 border-b admin-border">
            <h3 class="text-lg font-semibold admin-text-primary">General Settings</h3>
            <p class="text-sm admin-text-secondary">Basic system configuration and preferences</p>
        </div>
        
        <form class="p-6 space-y-8" id="generalForm">
            <!-- Site Information -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">Site Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="site_name" class="block text-sm font-medium admin-text-primary mb-2">Site Name</label>
                        <input type="text" id="site_name" name="site_name" value="Imhotion" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="site_url" class="block text-sm font-medium admin-text-primary mb-2">Site URL</label>
                        <input type="url" id="site_url" name="site_url" value="{{ config('app.url') }}" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="admin_email" class="block text-sm font-medium admin-text-primary mb-2">Admin Email</label>
                        <input type="email" id="admin_email" name="admin_email" value="admin@imhotion.com" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="timezone" class="block text-sm font-medium admin-text-primary mb-2">Timezone</label>
                        <select id="timezone" name="timezone" 
                                class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">Eastern Time</option>
                            <option value="America/Chicago">Central Time</option>
                            <option value="America/Denver">Mountain Time</option>
                            <option value="America/Los_Angeles">Pacific Time</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- System Preferences -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">System Preferences</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Maintenance Mode</h5>
                            <p class="text-sm admin-text-secondary">Enable maintenance mode to prevent public access</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">User Registration</h5>
                            <p class="text-sm admin-text-secondary">Allow new users to register accounts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="user_registration" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Email Verification</h5>
                            <p class="text-sm admin-text-secondary">Require email verification for new users</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_verification" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- File Upload Settings -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">File Upload Settings</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="max_file_size" class="block text-sm font-medium admin-text-primary mb-2">Max File Size (MB)</label>
                        <input type="number" id="max_file_size" name="max_file_size" value="10" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="allowed_extensions" class="block text-sm font-medium admin-text-primary mb-2">Allowed Extensions</label>
                        <input type="text" id="allowed_extensions" name="allowed_extensions" value="jpg,jpeg,png,gif,pdf,doc,docx" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Email Settings Tab -->
    <div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="px-6 py-4 border-b admin-border">
            <h3 class="text-lg font-semibold admin-text-primary">Email Configuration</h3>
            <p class="text-sm admin-text-secondary">Configure email settings and SMTP server</p>
        </div>
        
        <form class="p-6 space-y-8" id="emailForm">
            <!-- SMTP Settings -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">SMTP Settings</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="smtp_host" class="block text-sm font-medium admin-text-primary mb-2">SMTP Host</label>
                        <input type="text" id="smtp_host" name="smtp_host" value="smtp.gmail.com" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="smtp_port" class="block text-sm font-medium admin-text-primary mb-2">SMTP Port</label>
                        <input type="number" id="smtp_port" name="smtp_port" value="587" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="smtp_username" class="block text-sm font-medium admin-text-primary mb-2">SMTP Username</label>
                        <input type="text" id="smtp_username" name="smtp_username" value="noreply@imhotion.com" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="smtp_password" class="block text-sm font-medium admin-text-primary mb-2">SMTP Password</label>
                        <input type="password" id="smtp_password" name="smtp_password" placeholder="••••••••" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                </div>
            </div>
            
            <!-- Email Templates -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">Email Templates</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Welcome Email</h5>
                            <p class="text-sm admin-text-secondary">Send welcome email to new users</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="welcome_email" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Password Reset</h5>
                            <p class="text-sm admin-text-secondary">Send password reset emails</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="password_reset" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Security Settings Tab -->
    <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="px-6 py-4 border-b admin-border">
            <h3 class="text-lg font-semibold admin-text-primary">Security Settings</h3>
            <p class="text-sm admin-text-secondary">Configure security policies and authentication</p>
        </div>
        
        <form class="p-6 space-y-8" id="securityForm">
            <!-- Password Policy -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">Password Policy</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="min_password_length" class="block text-sm font-medium admin-text-primary mb-2">Minimum Password Length</label>
                        <input type="number" id="min_password_length" name="min_password_length" value="8" min="6" max="32" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="password_expiry" class="block text-sm font-medium admin-text-primary mb-2">Password Expiry (days)</label>
                        <input type="number" id="password_expiry" name="password_expiry" value="90" min="30" max="365" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                </div>
            </div>
            
            <!-- Security Features -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">Security Features</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Two-Factor Authentication</h5>
                            <p class="text-sm admin-text-secondary">Require 2FA for admin accounts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="two_factor_auth" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Login Attempts Limit</h5>
                            <p class="text-sm admin-text-secondary">Lock account after failed login attempts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="login_attempts_limit" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Session Timeout</h5>
                            <p class="text-sm admin-text-secondary">Auto-logout after inactivity</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="session_timeout" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Notifications Settings Tab -->
    <div x-show="activeTab === 'notifications'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="px-6 py-4 border-b admin-border">
            <h3 class="text-lg font-semibold admin-text-primary">Notification Settings</h3>
            <p class="text-sm admin-text-secondary">Configure system notifications and alerts</p>
        </div>
        
        <form class="p-6 space-y-8" id="notificationsForm">
            <!-- Email Notifications -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">Email Notifications</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">New User Registration</h5>
                            <p class="text-sm admin-text-secondary">Notify admin when new users register</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="new_user_notification" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">System Errors</h5>
                            <p class="text-sm admin-text-secondary">Notify admin of system errors</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="error_notification" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Security Alerts</h5>
                            <p class="text-sm admin-text-secondary">Notify admin of security issues</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="security_notification" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Integrations Settings Tab -->
    <div x-show="activeTab === 'integrations'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="px-6 py-4 border-b admin-border">
            <h3 class="text-lg font-semibold admin-text-primary">Integration Settings</h3>
            <p class="text-sm admin-text-secondary">Configure third-party integrations and APIs</p>
        </div>
        
        <form class="p-6 space-y-8" id="integrationsForm">
            <!-- API Settings -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">API Configuration</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="api_key" class="block text-sm font-medium admin-text-primary mb-2">API Key</label>
                        <input type="text" id="api_key" name="api_key" placeholder="Enter your API key" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                    <div>
                        <label for="api_secret" class="block text-sm font-medium admin-text-primary mb-2">API Secret</label>
                        <input type="password" id="api_secret" name="api_secret" placeholder="Enter your API secret" 
                               class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    </div>
                </div>
            </div>
            
            <!-- Third-party Services -->
            <div>
                <h4 class="text-md font-semibold admin-text-primary mb-4">Third-party Services</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Google Analytics</h5>
                            <p class="text-sm admin-text-secondary">Track website analytics</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="google_analytics" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                        <div>
                            <h5 class="font-medium admin-text-primary">Stripe Payment</h5>
                            <p class="text-sm admin-text-secondary">Enable payment processing</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="stripe_payment" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- System Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    <div class="admin-card p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 border admin-border rounded-xl flex items-center justify-center">
                    <i class="fas fa-database admin-text-primary text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Database Size</p>
                <p class="text-2xl font-bold admin-text-primary">2.4 MB</p>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 border admin-border rounded-xl flex items-center justify-center">
                    <i class="fas fa-server admin-text-primary text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Server Status</p>
                <p class="text-2xl font-bold text-green-600">Online</p>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 border admin-border rounded-xl flex items-center justify-center">
                    <i class="fas fa-shield-alt admin-text-primary text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Security Level</p>
                <p class="text-2xl font-bold text-green-600">High</p>
            </div>
        </div>
    </div>
</div>

<script>
function saveAllSettings() {
    // Collect all form data
    const forms = ['generalForm', 'emailForm', 'securityForm', 'notificationsForm', 'integrationsForm'];
    const allData = {};
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                allData[key] = value;
            }
        }
    });
    
    // Simulate saving
    console.log('Saving settings:', allData);
    alert('Settings saved successfully!');
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to default values?')) {
        // Reset all forms
        const forms = ['generalForm', 'emailForm', 'securityForm', 'notificationsForm', 'integrationsForm'];
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                form.reset();
            }
        });
        alert('Settings reset to defaults!');
    }
}

// Test email configuration
function testEmailConfig() {
    alert('Testing email configuration...');
}

// Test API connection
function testAPIConnection() {
    alert('Testing API connection...');
}
</script>
@endsection