@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="{{ route('admin.developers.index') }}" class="admin-text-secondary hover:admin-text-primary mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold admin-text-primary">Add New Developer</h1>
            <p class="admin-text-secondary mt-2">Create a new developer account with specialization and skills</p>
        </div>
    </div>
</div>

<!-- Form -->
<form method="POST" action="{{ route('admin.developers.store') }}" class="admin-card">
            @csrf
            <div class="p-6">
                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium admin-text-primary mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium admin-text-primary mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium admin-text-primary mb-2">Password *</label>
                            <input type="password" id="password" name="password" required
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium admin-text-primary mb-2">Confirm Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary">
                        </div>
                    </div>
                </div>

                <!-- Specialization & Skills -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Specialization & Skills</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="specialization_id" class="block text-sm font-medium admin-text-primary mb-2">Specialization *</label>
                            <select id="specialization_id" name="specialization_id" required
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('specialization_id') border-red-500 @enderror">
                                <option value="">Select a specialization</option>
                                @foreach($specializations->groupBy('category') as $category => $specs)
                                    <optgroup label="{{ ucfirst(str_replace('_', ' ', $category)) }}">
                                        @foreach($specs as $spec)
                                            <option value="{{ $spec->id }}" {{ old('specialization_id') == $spec->id ? 'selected' : '' }}>
                                                {{ $spec->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('specialization_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="experience_level" class="block text-sm font-medium admin-text-primary mb-2">Experience Level *</label>
                            <select id="experience_level" name="experience_level" required
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('experience_level') border-red-500 @enderror">
                                <option value="">Select experience level</option>
                                <option value="junior" {{ old('experience_level') == 'junior' ? 'selected' : '' }}>Junior (0-2 years)</option>
                                <option value="mid" {{ old('experience_level') == 'mid' ? 'selected' : '' }}>Mid-Level (3-5 years)</option>
                                <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>Senior (6+ years)</option>
                            </select>
                            @error('experience_level')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="skills" class="block text-sm font-medium admin-text-primary mb-2">Skills & Expertise</label>
                            <div class="skills-tags-container border admin-border rounded-lg p-3 bg-admin-bg-secondary min-h-[100px]">
                                <div id="skills-tags" class="flex flex-wrap gap-2 mb-3">
                                    <!-- Tags will be added here dynamically -->
                                </div>
                                <input type="text" id="skills-input" placeholder="Type a skill and press Enter..." 
                                    class="w-full border-none bg-transparent admin-text-primary placeholder-admin-text-secondary focus:outline-none focus:ring-0">
                                <input type="hidden" id="skills-hidden" name="skills[]" value="">
                            </div>
                            <p class="text-sm admin-text-secondary mt-1">Add skills by typing and pressing Enter. Click on tags to remove them.</p>
                            @error('skills')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium admin-text-primary mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium admin-text-primary mb-2">Country</label>
                            <input type="text" id="country" name="country" value="{{ old('country') }}" placeholder="e.g., US, UK, DE"
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('country') border-red-500 @enderror">
                            @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium admin-text-primary mb-2">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}"
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium admin-text-primary mb-2">Address</label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}"
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary @error('address') border-red-500 @enderror">
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Availability & Working Hours -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Availability & Working Hours</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" {{ old('is_available') ? 'checked' : '' }}
                                    class="rounded admin-border text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm font-medium admin-text-primary">Available for new projects</span>
                            </label>
                            <p class="text-sm admin-text-secondary mt-1">Check this if the developer is currently available to take on new projects</p>
                        </div>
                        <div>
                            <label for="working_hours" class="block text-sm font-medium admin-text-primary mb-2">Working Hours (Optional)</label>
                            <input type="text" id="working_hours" name="working_hours" value="{{ old('working_hours') }}" placeholder="e.g., 9 AM - 5 PM EST"
                                class="w-full border admin-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-admin-bg-secondary admin-text-primary">
                            <p class="text-sm admin-text-secondary mt-1">Preferred working hours or timezone</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t admin-border">
                    <a href="{{ route('admin.developers.index') }}" class="admin-button admin-button-secondary">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="admin-button">
                        <i class="fas fa-save mr-2"></i>Create Developer
                    </button>
                </div>
            </div>
        </form>
    </div>
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

    // Update hidden input
    function updateHiddenInput() {
        skillsHidden.value = skills.join(',');
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

