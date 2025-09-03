@extends('layouts.dashboard')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
    <!-- Mini Cart Component -->
    @include('components.mini-cart')

    <!-- Profile Section -->
    <div style="background: #0a1428; border-radius: 12px; padding: 20px; color: #ffffff;">
        <div class="profile-section">
            <h2 style="color: #ffffff; font-size: 20px; font-weight: 600; margin-bottom: 25px; font-family: var(--font-sans)">
                Profile Settings
            </h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <!-- Profile Info -->
                <div style="background: #001f4c; border: 1px solid #7fa7e1; border-radius: 12px; padding: 20px;">
                    <h3 style="color: #ffffff; font-size: 16px; font-weight: 600; margin-bottom: 20px;">
                        Account Information
                    </h3>
                    <div style="space-y: 16px;">
                        <div style="margin-bottom: 16px;">
                            <label style="color: #8fa8cc; font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Full Name</label>
                            <div style="color: #ffffff; font-size: 14px; font-weight: 500;">{{ Auth::user()->name }}</div>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <label style="color: #8fa8cc; font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Email Address</label>
                            <div style="color: #ffffff; font-size: 14px; font-weight: 500;">{{ Auth::user()->email }}</div>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <label style="color: #8fa8cc; font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Member Since</label>
                            <div style="color: #ffffff; font-size: 14px; font-weight: 500;">{{ Auth::user()->created_at->format('F d, Y') }}</div>
                        </div>
                        @if(Auth::user()->role)
                            <div style="margin-bottom: 16px;">
                                <label style="color: #8fa8cc; font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Account Type</label>
                                <div style="color: #ffffff; font-size: 14px; font-weight: 500;">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Account Stats -->
                <div style="background: #001f4c; border: 1px solid #7fa7e1; border-radius: 12px; padding: 20px;">
                    <h3 style="color: #ffffff; font-size: 16px; font-weight: 600; margin-bottom: 20px;">
                        Account Statistics
                    </h3>
                    <div style="space-y: 16px;">
                        <div style="background: #121e2f; border: 1px solid #7fa7e1; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="color: #8fa8cc; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Total Projects</div>
                                    <div style="color: #ffffff; font-size: 24px; font-weight: 700;">{{ $active->count() + $finalized->count() }}</div>
                                </div>
                                <div style="color: #7fa7e1;">
                                    <svg style="width: 32px; height: 32px;" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div style="background: #121e2f; border: 1px solid #7fa7e1; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="color: #8fa8cc; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Active Projects</div>
                                    <div style="color: #ffffff; font-size: 24px; font-weight: 700;">{{ $counts['active'] ?? 0 }}</div>
                                </div>
                                <div style="color: #7fa7e1;">
                                    <svg style="width: 32px; height: 32px;" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div style="background: #121e2f; border: 1px solid #7fa7e1; border-radius: 8px; padding: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="color: #8fa8cc; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Finalized Projects</div>
                                    <div style="color: #ffffff; font-size: 24px; font-weight: 700;">{{ $counts['finalized'] ?? 0 }}</div>
                                </div>
                                <div style="color: #7fa7e1;">
                                    <svg style="width: 32px; height: 32px;" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 16px; margin-top: 24px;">
                <a href="/client"
                   style="background: #3366cc; color: #ffffff; padding: 10px 20px; border: 1px solid #7fa7e1; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: opacity 0.2s ease;"
                   onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    View Full Client Area
                </a>
                <a href="/"
                   style="background: #121e2f; color: #ffffff; padding: 10px 20px; border: 1px solid #7fa7e1; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: opacity 0.2s ease;"
                   onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
@media (max-width: 768px) {
    .profile-section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
