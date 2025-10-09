@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">Edit User</h2>
            <p class="admin-text-secondary mt-2">Update user information and settings</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users') }}" class="admin-button admin-button-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
            <a href="{{ route('admin.users.show', $user) }}" class="admin-button admin-button-secondary">
                <i class="fas fa-eye mr-2"></i>View User
            </a>
        </div>
    </div>
</div>

<!-- Edit User Form -->
<div class="admin-card">
    <div class="px-6 py-4 border-b admin-border">
        <div class="flex items-center">
            @if($user->avatar)
                <img class="h-12 w-12 rounded-full object-cover mr-4" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
            @else
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-4">
                    <span class="text-lg font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                </div>
            @endif
            <div>
                <h3 class="text-lg font-semibold admin-text-primary">{{ $user->name }}</h3>
                <p class="text-sm admin-text-secondary">{{ $user->email }}</p>
            </div>
        </div>
    </div>
    
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-6">
                <h4 class="text-md font-semibold admin-text-primary border-b admin-border pb-2">Basic Information</h4>
                
                <div>
                    <label for="name" class="block text-sm font-medium admin-text-primary mb-2">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium admin-text-primary mb-2">Email Address *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium admin-text-primary mb-2">New Password</label>
                    <input type="password" id="password" name="password"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('password') border-red-500 @enderror">
                    <p class="text-sm admin-text-secondary mt-1">Leave blank to keep current password</p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium admin-text-primary mb-2">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                </div>
            </div>
            
            <!-- Role and Status -->
            <div class="space-y-6">
                <h4 class="text-md font-semibold admin-text-primary border-b admin-border pb-2">Role & Status</h4>
                
                <div>
                    <label for="role" class="block text-sm font-medium admin-text-primary mb-2">Role *</label>
                    <select id="role" name="role" required
                            class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('role') border-red-500 @enderror">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="developer" {{ old('role', $user->role) === 'developer' ? 'selected' : '' }}>Developer</option>
                        <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Client</option>
                        <option value="administrator" {{ old('role', $user->role) === 'administrator' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium admin-text-primary mb-2">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="country" class="block text-sm font-medium admin-text-primary mb-2">Country</label>
                    <input type="text" id="country" name="country" value="{{ old('country', $user->country) }}"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('country') border-red-500 @enderror">
                    @error('country')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $user->is_available ?? true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_available" class="ml-2 block text-sm admin-text-primary">
                        User is available/active
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Additional Information -->
        <div class="mt-8">
            <h4 class="text-md font-semibold admin-text-primary border-b admin-border pb-2 mb-6">Additional Information</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="specialization" class="block text-sm font-medium admin-text-primary mb-2">Specialization</label>
                    <input type="text" id="specialization" name="specialization" value="{{ old('specialization', $user->specialization) }}"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('specialization') border-red-500 @enderror">
                    @error('specialization')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="avatar" class="block text-sm font-medium admin-text-primary mb-2">Profile Picture</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('avatar') border-red-500 @enderror">
                    @if($user->avatar)
                        <p class="text-sm admin-text-secondary mt-1">Current: {{ basename($user->avatar) }}</p>
                    @endif
                    @error('avatar')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="skills" class="block text-sm font-medium admin-text-primary mb-2">Skills (one per line)</label>
                <textarea id="skills" name="skills" rows="4" placeholder="Enter skills, one per line..."
                          class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('skills') border-red-500 @enderror">{{ old('skills', is_array($user->skills) ? implode("\n", $user->skills) : $user->skills) }}</textarea>
                <p class="text-sm admin-text-secondary mt-1">Enter each skill on a new line</p>
                @error('skills')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t admin-border">
            <a href="{{ route('admin.users') }}" class="admin-button admin-button-secondary">
                Cancel
            </a>
            <button type="submit" class="admin-button">
                <i class="fas fa-save mr-2"></i>Update User
            </button>
        </div>
    </form>
</div>
@endsection
