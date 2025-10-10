@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">Create New User</h2>
            <p class="admin-text-secondary mt-2">Add a new user to the platform</p>
        </div>
        <div>
            <a href="{{ route('admin.users') }}" class="admin-button admin-button-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>
    </div>
</div>

<!-- Create User Form -->
<div class="admin-card">
    <div class="px-6 py-4 border-b admin-border">
        <h3 class="text-lg font-semibold admin-text-primary">User Information</h3>
        <p class="text-sm admin-text-secondary">Fill in the user details below</p>
    </div>
    
    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-6">
                <h4 class="text-md font-semibold admin-text-primary border-b admin-border pb-2">Basic Information</h4>
                
                <div>
                    <label for="name" class="block text-sm font-medium admin-text-primary mb-2">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium admin-text-primary mb-2">Email Address *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium admin-text-primary mb-2">Password *</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium admin-text-primary mb-2">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
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
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                        <option value="administrator" {{ old('role') === 'administrator' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium admin-text-primary mb-2">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="country" class="block text-sm font-medium admin-text-primary mb-2">Country</label>
                    <input type="text" id="country" name="country" value="{{ old('country') }}"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('country') border-red-500 @enderror">
                    @error('country')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
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
                    <input type="text" id="specialization" name="specialization" value="{{ old('specialization') }}"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('specialization') border-red-500 @enderror">
                    @error('specialization')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="avatar" class="block text-sm font-medium admin-text-primary mb-2">Profile Picture</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*"
                           class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('avatar') border-red-500 @enderror">
                    @error('avatar')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="skills" class="block text-sm font-medium admin-text-primary mb-2">Skills & Expertise</label>
                <div class="skills-tags-container border admin-border rounded-lg p-3 bg-admin-bg-secondary min-h-[100px]">
                    <div id="skills-tags" class="flex flex-wrap gap-2 mb-3">
                        <!-- Tags will be added here dynamically -->
                    </div>
                    <input type="text" id="skills-input" placeholder="Type a skill and press Enter..." 
                        class="w-full border-none bg-transparent admin-text-primary placeholder-admin-text-secondary focus:outline-none focus:ring-0">
                    <input type="hidden" id="skills-hidden" name="skills" value="">
                </div>
                <p class="text-sm admin-text-secondary mt-1">Add skills by typing and pressing Enter. Click on tags to remove them.</p>
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
                <i class="fas fa-save mr-2"></i>Create User
            </button>
        </div>
    </form>
</div>

<!-- Skills Tags JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const skillsInput = document.getElementById('skills-input');
    const skillsTags = document.getElementById('skills-tags');
    const skillsHidden = document.getElementById('skills-hidden');
    const skills = [];

    // Add skill tag
    function addSkill(skill) {
        if (skill.trim() && !skills.includes(skill.trim())) {
            skills.push(skill.trim());
            updateTags();
            updateHiddenInput();
        }
    }

    // Remove skill tag
    function removeSkill(skill) {
        const index = skills.indexOf(skill);
        if (index > -1) {
            skills.splice(index, 1);
            updateTags();
            updateHiddenInput();
        }
    }

    // Update tags display
    function updateTags() {
        skillsTags.innerHTML = '';
        skills.forEach(skill => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 cursor-pointer transition-colors';
            tag.innerHTML = `${skill} <i class="fas fa-times ml-2 text-xs"></i>`;
            tag.addEventListener('click', () => removeSkill(skill));
            skillsTags.appendChild(tag);
        });
    }

    // Update hidden inputs
    function updateHiddenInput() {
        // Remove existing skill inputs
        const existingInputs = document.querySelectorAll('input[name="skills[]"]');
        existingInputs.forEach(input => input.remove());
        
        // Add new skill inputs
        skills.forEach(skill => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'skills[]';
            input.value = skill;
            skillsHidden.parentNode.insertBefore(input, skillsHidden);
        });
    }

    // Handle input events
    skillsInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addSkill(this.value);
            this.value = '';
        }
    });

    // Handle comma separation
    skillsInput.addEventListener('input', function(e) {
        if (e.target.value.includes(',')) {
            const newSkills = e.target.value.split(',').map(s => s.trim()).filter(s => s);
            newSkills.forEach(skill => addSkill(skill));
            this.value = '';
        }
    });

    // Load existing skills from old input
    const existingSkills = @json(old('skills', []));
    if (existingSkills && existingSkills.length > 0) {
        existingSkills.forEach(skill => addSkill(skill));
    }
});
</script>
@endsection
