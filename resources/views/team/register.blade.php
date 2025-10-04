@extends('layouts.guest')

@section('content')
<style>
/* Skills Tags Styling */
.skills-container {
    margin-top: 8px;
}

.skills-input-wrapper {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.skills-input-wrapper input {
    flex: 1;
    margin-bottom: 0;
}

.add-skill-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 48px;
}

.add-skill-btn:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
}

.skills-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    min-height: 40px;
    padding: 8px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
}

.skill-tag {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    gap: 6px;
    animation: slideIn 0.3s ease;
}

.skill-tag .remove-skill {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #ffffff;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 10px;
}

.skill-tag .remove-skill:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.skills-tags:empty::before {
    content: "No skills added yet";
    color: #6c757d;
    font-style: italic;
    font-size: 14px;
}
</style>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Join Our Team</h1>
            <p class="text-xl text-gray-600">Register as a developer and start working on exciting projects</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('team.register.store') }}" class="space-y-8">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                    placeholder="Enter your full name">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                    placeholder="your.email@example.com">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <input type="password" id="password" name="password" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                    placeholder="Minimum 8 characters">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Confirm your password">
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Professional Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="specialization_id" class="block text-sm font-medium text-gray-700 mb-2">Specialization *</label>
                                <select id="specialization_id" name="specialization_id" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('specialization_id') border-red-500 @enderror">
                                    <option value="">Select your specialization</option>
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
                                <label for="experience_level" class="block text-sm font-medium text-gray-700 mb-2">Experience Level *</label>
                                <select id="experience_level" name="experience_level" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('experience_level') border-red-500 @enderror">
                                    <option value="">Select your experience level</option>
                                    <option value="junior" {{ old('experience_level') == 'junior' ? 'selected' : '' }}>Junior (0-2 years)</option>
                                    <option value="mid" {{ old('experience_level') == 'mid' ? 'selected' : '' }}>Mid-Level (3-5 years)</option>
                                    <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>Senior (6+ years)</option>
                                </select>
                                @error('experience_level')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="working_hours" class="block text-sm font-medium text-gray-700 mb-2">Working Hours</label>
                                <input type="text" id="working_hours" name="working_hours" value="{{ old('working_hours') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 9 AM - 5 PM EST">
                            </div>
                            <div class="md:col-span-2">
                                <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">Skills & Expertise</label>
                                <div class="skills-container">
                                    <div class="skills-input-wrapper">
                                        <input type="text" id="skillInput" placeholder="Type a skill and press Enter" 
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" id="addSkillBtn" class="add-skill-btn">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="skills-tags" id="skillsTags">
                                        @if(old('skills'))
                                            @php
                                                $skills = is_string(old('skills')) ? json_decode(old('skills'), true) : old('skills');
                                                $skills = is_array($skills) ? $skills : [];
                                            @endphp
                                            @foreach($skills as $skill)
                                                <span class="skill-tag">
                                                    {{ $skill }}
                                                    <button type="button" class="remove-skill" data-skill="{{ $skill }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <input type="hidden" name="skills" id="skillsInput" value="{{ old('skills', '') }}">
                                </div>
                                @error('skills')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Professional Bio</label>
                                <textarea id="bio" name="bio" rows="3" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bio') border-red-500 @enderror"
                                    placeholder="Tell us about yourself, your experience, and what you're passionate about...">{{ old('bio') }}</textarea>
                                @error('bio')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                    placeholder="+1 (555) 123-4567">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <input type="text" id="country" name="country" value="{{ old('country') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('country') border-red-500 @enderror"
                                    placeholder="e.g., US, UK, DE">
                                @error('country')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('city') border-red-500 @enderror"
                                    placeholder="Your city">
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                                    placeholder="Your address">
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Portfolio & Links -->
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Portfolio & Links</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="portfolio_url" class="block text-sm font-medium text-gray-700 mb-2">Portfolio Website</label>
                                <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('portfolio_url') border-red-500 @enderror"
                                    placeholder="https://yourportfolio.com">
                                @error('portfolio_url')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-2">LinkedIn Profile</label>
                                <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('linkedin_url') border-red-500 @enderror"
                                    placeholder="https://linkedin.com/in/yourprofile">
                                @error('linkedin_url')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="github_url" class="block text-sm font-medium text-gray-700 mb-2">GitHub Profile</label>
                                <input type="url" id="github_url" name="github_url" value="{{ old('github_url') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('github_url') border-red-500 @enderror"
                                    placeholder="https://github.com/yourusername">
                                @error('github_url')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div>
                        <div class="flex items-start">
                            <input type="checkbox" id="terms_accepted" name="terms_accepted" required
                                class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('terms_accepted') border-red-500 @enderror">
                            <label for="terms_accepted" class="ml-3 text-sm text-gray-700">
                                I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> and <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a> *
                            </label>
                        </div>
                        @error('terms_accepted')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center pt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-4 rounded-lg font-semibold text-lg transition-colors">
                            <i class="fas fa-rocket mr-2"></i>Join Our Team
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-8">
            <p class="text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">Sign in here</a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Skills tags functionality
    const skillInput = document.getElementById('skillInput');
    const addSkillBtn = document.getElementById('addSkillBtn');
    const skillsTags = document.getElementById('skillsTags');
    const skillsInput = document.getElementById('skillsInput');
    
    let skills = [];
    
    // Load existing skills
    if (skillsInput.value) {
        try {
            skills = JSON.parse(skillsInput.value);
            if (!Array.isArray(skills)) {
                skills = [];
            }
        } catch (e) {
            skills = [];
        }
    }
    
    // Update hidden input
    function updateSkillsInput() {
        skillsInput.value = JSON.stringify(skills);
    }
    
    // Add skill
    function addSkill(skill) {
        skill = skill.trim();
        if (skill && !skills.includes(skill)) {
            skills.push(skill);
            renderSkills();
            updateSkillsInput();
            skillInput.value = '';
        }
    }
    
    // Remove skill
    function removeSkill(skill) {
        skills = skills.filter(s => s !== skill);
        renderSkills();
        updateSkillsInput();
    }
    
    // Render skills
    function renderSkills() {
        skillsTags.innerHTML = '';
        skills.forEach(skill => {
            const tag = document.createElement('span');
            tag.className = 'skill-tag';
            tag.innerHTML = `
                ${skill}
                <button type="button" class="remove-skill" data-skill="${skill}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            skillsTags.appendChild(tag);
        });
    }
    
    // Event listeners
    addSkillBtn.addEventListener('click', () => {
        addSkill(skillInput.value);
    });
    
    skillInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addSkill(skillInput.value);
        }
    });
    
    skillsTags.addEventListener('click', (e) => {
        if (e.target.closest('.remove-skill')) {
            const skill = e.target.closest('.remove-skill').dataset.skill;
            removeSkill(skill);
        }
    });
    
    // Initial render
    renderSkills();
});
</script>
@endsection
