@extends('layouts.developer')

@section('page-title', 'Profile Settings')

@section('content')
<style>
/* Dark theme profile page styling */
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
}

.profile-card {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    margin-bottom: 24px;
    overflow: hidden;
}

.profile-card-header {
    background: #2a2a2a;
    padding: 20px 24px;
    border-bottom: 1px solid #3a3a3a;
}

.profile-card-title {
    color: #ffffff;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.profile-card-content {
    padding: 24px;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
}

.form-input {
    width: 100%;
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    border-radius: 8px;
    padding: 12px 16px;
    color: #ffffff;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-input::placeholder {
    color: #8b8b8b;
}

.form-textarea {
    width: 100%;
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    border-radius: 8px;
    padding: 12px 16px;
    color: #ffffff;
    font-size: 14px;
    resize: vertical;
    min-height: 100px;
    transition: all 0.3s ease;
}

.form-textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-select {
    width: 100%;
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    border-radius: 8px;
    padding: 12px 16px;
    color: #ffffff;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-checkbox {
    width: 18px;
    height: 18px;
    accent-color: #667eea;
}

.form-checkbox-label {
    color: #ffffff;
    font-size: 14px;
    margin-left: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
}

.btn-secondary {
    background: #2a2a2a;
    color: #ffffff;
    border: 1px solid #3a3a3a;
}

.btn-secondary:hover {
    background: #3a3a3a;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
}

.error-message {
    color: #ff6b6b;
    font-size: 12px;
    margin-top: 4px;
}

.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

@media (max-width: 768px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
}

/* Skills Tags Styling */
.skills-container {
    margin-top: 8px;
}

.skills-input-wrapper {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.skills-input-wrapper .form-input {
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
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
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
    color: #8b8b8b;
    font-style: italic;
    font-size: 14px;
}
</style>

<div class="profile-container">
    <!-- Profile Form -->
    <form method="POST" action="{{ route('developer.profile.update') }}">
        @csrf
        
        <!-- Personal Information -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title">Personal Information</h3>
            </div>
            <div class="profile-card-content">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                            class="form-input @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                            class="form-input @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                            class="form-input @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Experience Level</label>
                        <select name="experience_level" class="form-select @error('experience_level') border-red-500 @enderror">
                            <option value="">Select Experience Level</option>
                            <option value="junior" {{ old('experience_level', $user->experience_level) === 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ old('experience_level', $user->experience_level) === 'mid' ? 'selected' : '' }}>Mid-Level</option>
                            <option value="senior" {{ old('experience_level', $user->experience_level) === 'senior' ? 'selected' : '' }}>Senior</option>
                        </select>
                        @error('experience_level')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title">Professional Information</h3>
            </div>
            <div class="profile-card-content">
                <div class="form-group">
                    <label class="form-label">Specialization</label>
                    <select name="specialization_id" class="form-select @error('specialization_id') border-red-500 @enderror">
                        <option value="">Select Specialization</option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}" {{ old('specialization_id', $user->specialization_id) == $specialization->id ? 'selected' : '' }}>
                                {{ $specialization->name }} ({{ $specialization->category }})
                            </option>
                        @endforeach
                    </select>
                    @error('specialization_id')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Skills</label>
                    <div class="skills-container">
                        <div class="skills-input-wrapper">
                            <input type="text" id="skillInput" placeholder="Type a skill and press Enter" class="form-input">
                            <button type="button" id="addSkillBtn" class="add-skill-btn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="skills-tags" id="skillsTags">
                            @if($user->skills)
                                @php
                                    $skills = is_array($user->skills) ? $user->skills : (is_string($user->skills) ? json_decode($user->skills, true) : []);
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
                        <input type="hidden" name="skills" id="skillsInput" value="{{ old('skills', is_array($user->skills) ? json_encode($user->skills) : $user->skills) }}">
                    </div>
                    @error('skills')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" rows="3" placeholder="Tell us about yourself, your experience, and what you bring to the team..." 
                        class="form-textarea @error('bio') border-red-500 @enderror">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title">Contact Information</h3>
            </div>
            <div class="profile-card-content">
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                        class="form-input @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" 
                            class="form-input @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}" 
                            class="form-input @error('country') border-red-500 @enderror">
                        @error('country')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio Links -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title">Portfolio & Social Links</h3>
            </div>
            <div class="profile-card-content">
                <div class="form-group">
                    <label class="form-label">Portfolio Website</label>
                    <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $user->portfolio_url) }}" 
                        placeholder="https://yourportfolio.com" 
                        class="form-input @error('portfolio_url') border-red-500 @enderror">
                    @error('portfolio_url')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">LinkedIn Profile</label>
                        <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" 
                            placeholder="https://linkedin.com/in/yourprofile" 
                            class="form-input @error('linkedin_url') border-red-500 @enderror">
                        @error('linkedin_url')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">GitHub Profile</label>
                        <input type="url" name="github_url" value="{{ old('github_url', $user->github_url) }}" 
                            placeholder="https://github.com/yourusername" 
                            class="form-input @error('github_url') border-red-500 @enderror">
                        @error('github_url')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Availability -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title">Availability</h3>
            </div>
            <div class="profile-card-content">
                <div class="form-group">
                    <div style="display: flex; align-items: center;">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $user->is_available) ? 'checked' : '' }} 
                            class="form-checkbox">
                        <label class="form-checkbox-label">
                            I am currently available for new projects
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; gap: 16px; margin-top: 32px;">
            <a href="{{ route('developer.dashboard') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i>Update Profile
            </button>
        </div>
    </form>
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
    
    // Success message
    @if(session('success'))
    const successMessage = document.createElement('div');
    successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successMessage.innerHTML = '<i class="fas fa-check mr-2"></i>{{ session("success") }}';
    document.body.appendChild(successMessage);
    
    setTimeout(() => {
        successMessage.remove();
    }, 5000);
    @endif
});
</script>
@endsection
