@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <!-- Dashboard Container -->
    <div class="flex">
        <!-- Left Sidebar -->
        <div class="w-64 bg-[#0a1428] h-screen sticky top-0">
            <div class="p-6">
                <h2 class="text-white text-xl font-bold mb-8">Dashboard</h2>
                
                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <a href="#home" onclick="showSection('home')" class="nav-item active flex items-center px-4 py-3 text-white bg-[#2563eb] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-3a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Home
                    </a>
                    
                    <a href="#transactions" onclick="showSection('transactions')" class="nav-item flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-[#1e40af] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        Transactions
                    </a>
                    
                    <a href="#profile" onclick="showSection('profile')" class="nav-item flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-[#1e40af] rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Profile
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-[#0a1428]">
            <!-- Home Section -->
            <div id="home-section" class="section-content">
                <!-- Virtual Cart -->
                <div class="p-8 border-b border-[#7fa7e1]/20">
                    <h3 class="text-white text-2xl font-bold mb-6">🛒 Your Cart</h3>
                    
                    <div id="cart-items" class="space-y-4 mb-6">
                        @if(session('cart'))
                            @foreach(session('cart') as $item)
                                <div class="bg-[#001f4c] border border-[#7fa7e1] rounded-lg p-4 flex items-center justify-between hover:bg-[#002a66] transition-all duration-300">
                                    <div>
                                        <h4 class="text-white font-semibold">{{ $item['title'] }}</h4>
                                        <p class="text-gray-300 text-sm">{{ $item['category'] ?? 'Service' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-white font-bold text-lg">€{{ number_format($item['price'], 2) }}</div>
                                        <div class="text-gray-400 text-sm">{{ $item['days'] ?? 1 }} day(s)</div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Checkout Button -->
                            <div class="pt-4 border-t border-[#7fa7e1]/20">
                                <button onclick="proceedToCheckout()" class="w-full bg-gradient-to-r from-[#2563eb] to-[#1d4ed8] text-white py-4 px-6 rounded-lg font-bold text-lg hover:from-[#1d4ed8] hover:to-[#1e40af] transition-all duration-300 flex items-center justify-center space-x-3">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                    <span>Secure Checkout with Mollie</span>
                                    <div class="flex space-x-1">
                                        <img src="https://www.mollie.com/external/icons/payment-methods/visa.svg" alt="Visa" class="h-6">
                                        <img src="https://www.mollie.com/external/icons/payment-methods/mastercard.svg" alt="Mastercard" class="h-6">
                                        <img src="https://www.mollie.com/external/icons/payment-methods/ideal.svg" alt="iDEAL" class="h-6">
                                    </div>
                                </button>
                            </div>
                        @else
                            <div class="bg-[#001f4c] border border-[#7fa7e1] rounded-lg p-8 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-gray-300 text-lg mb-4">Your cart is empty</p>
                                <p class="text-gray-400">Add services from the catalog below to get started!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Client Area Copy (from /client) -->
                <div class="p-8">
                    <h3 class="text-white text-2xl font-bold mb-6">📊 Project Overview</h3>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-3 gap-4 mb-8 max-w-lg mx-auto">
                        <div class="bg-[#001f4c] border border-[#7fa7e1] rounded-lg p-4 text-center hover:bg-[#002a66] hover:scale-105 transition-all duration-300 cursor-pointer">
                            <div class="text-white text-sm font-medium mb-2">Active</div>
                            <div class="bg-[#19355e] border border-[#7fa7e1] text-white text-sm font-semibold py-1 px-3 rounded-lg inline-block hover:bg-[#1c3d68] transition-colors">{{ $counts['active'] }}</div>
                        </div>
                        <div class="bg-[#001f4c] border border-[#7fa7e1] rounded-lg p-4 text-center hover:bg-[#002a66] hover:scale-105 transition-all duration-300 cursor-pointer">
                            <div class="text-white text-sm font-medium mb-2">Balance</div>
                            <div class="bg-[#2e5182] border border-[#7fa7e1] text-white text-sm font-semibold py-1 px-3 rounded-lg inline-block hover:bg-[#345a90] transition-colors">{{ $counts['balance'] }} Days</div>
                        </div>
                        <div class="bg-[#001f4c] border border-[#7fa7e1] rounded-lg p-4 text-center hover:bg-[#002a66] hover:scale-105 transition-all duration-300 cursor-pointer">
                            <div class="text-white text-sm font-medium mb-2">Finalized</div>
                            <div class="bg-[#19355e] border border-[#7fa7e1] text-white text-sm font-semibold py-1 px-3 rounded-lg inline-block hover:bg-[#1c3d68] transition-colors">{{ $counts['finalized'] }}</div>
                        </div>
                    </div>

                    <!-- Active Projects -->
                    <div class="mb-8">
                        <h4 class="text-white text-xl font-semibold mb-4">🚀 Active Projects</h4>
                        <div class="bg-[#001f4c] rounded-lg overflow-hidden">
                            @forelse($active as $project)
                                <div class="border-b border-[#7fa7e1]/20 last:border-b-0 hover:bg-[#002a66] hover:scale-105 transition-all duration-500 cursor-pointer" style="height: 80px;">
                                    <div class="p-4 flex items-center">
                                        <div class="w-10 h-10 bg-[#002a66] border border-[#7fa7e1] rounded-lg flex items-center justify-center text-white font-bold mr-4 flex-shrink-0">
                                            25{{ str_pad($project->id, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-white font-semibold truncate">{{ $project->title }}</h5>
                                            <p class="text-gray-300 text-sm truncate">{{ $project->topic ?: 'No description' }}</p>
                                        </div>
                                        <div class="text-right mr-4 hidden md:block">
                                            <div class="text-white text-sm">
                                                {{ $project->delivery_date ? 'Due ' . $project->delivery_date->format('M j') : 'No deadline' }}
                                            </div>
                                            <div class="text-gray-400 text-xs">{{ $project->used_days }}/{{ $project->estimated_days }} days</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($project->status === 'completed')
                                                <span class="bg-green-600 text-white text-xs px-3 py-1 rounded-full font-medium">Completed</span>
                                            @elseif($project->status === 'in_progress')
                                                <span class="bg-amber-600 text-white text-xs px-3 py-1 rounded-full font-medium">In Progress</span>
                                            @elseif($project->status === 'pending')
                                                <span class="bg-orange-500 text-white text-xs px-3 py-1 rounded-full font-medium">Pending</span>
                                            @elseif($project->status === 'new')
                                                <span class="bg-cyan-600 text-white text-xs px-3 py-1 rounded-full font-medium">New</span>
                                            @endif
                                            <button class="bg-[#2563eb] hover:bg-[#1d4ed8] text-white text-xs px-3 py-1 rounded-full font-medium transition-colors">
                                                Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                    No active projects yet
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Finalized Projects -->
                    <div>
                        <h4 class="text-white text-xl font-semibold mb-4">✅ Finalized Projects</h4>
                        <div class="bg-[#121e2f] rounded-lg overflow-hidden">
                            @forelse($finalized as $project)
                                <div class="border-b border-[#7fa7e1]/20 last:border-b-0 hover:bg-[#1a2a40] hover:scale-105 transition-all duration-500 cursor-pointer" style="height: 80px;">
                                    <div class="p-4 flex items-center">
                                        <div class="w-10 h-10 bg-[#1a2a40] border border-[#7fa7e1] rounded-lg flex items-center justify-center text-white font-bold mr-4 flex-shrink-0">
                                            25{{ str_pad($project->id, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-white font-semibold truncate">{{ $project->title }}</h5>
                                            <p class="text-gray-300 text-sm truncate">{{ $project->topic ?: 'No description' }}</p>
                                        </div>
                                        <div class="text-right mr-4 hidden md:block">
                                            <div class="text-white text-sm">
                                                {{ $project->delivery_date ? 'Delivered ' . $project->delivery_date->format('M j') : 'No date' }}
                                            </div>
                                            <div class="text-gray-400 text-xs">{{ $project->used_days }} days used</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($project->status === 'finalized')
                                                <span class="bg-gray-600 text-white text-xs px-3 py-1 rounded-full font-medium">Finalized</span>
                                            @elseif($project->status === 'cancelled')
                                                <span class="bg-red-600 text-white text-xs px-3 py-1 rounded-full font-medium">Cancelled</span>
                                            @endif
                                            <button class="bg-[#2563eb] hover:bg-[#1d4ed8] text-white text-xs px-3 py-1 rounded-full font-medium transition-colors">
                                                Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    No finalized projects yet
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Section -->
            <div id="transactions-section" class="section-content hidden">
                <div class="p-8">
                    <h3 class="text-white text-2xl font-bold mb-6">💳 Transaction History</h3>
                    
                    <div class="bg-[#001f4c] rounded-lg overflow-hidden">
                        @forelse($userPurchases as $purchase)
                            <div class="border-b border-[#7fa7e1]/20 last:border-b-0 p-4 hover:bg-[#002a66] transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($purchase->pricingItem)
                                            <h4 class="text-white font-semibold">{{ optional($purchase->pricingItem)->title }}</h4>
                                            <p class="text-gray-300 text-sm">{{ $purchase->created_at->format('M j, Y H:i') }}</p>
                                            <p class="text-gray-400 text-xs">Payment ID: {{ $purchase->mollie_payment_id }}</p>
                                        @else
                                            @php $items = is_array($purchase->payment_data) ? $purchase->payment_data : json_decode($purchase->payment_data, true) ?? []; @endphp
                                            <h4 class="text-white font-semibold">Multiple items</h4>
                                            <p class="text-gray-300 text-sm">{{ $purchase->created_at->format('M j, Y H:i') }}</p>
                                            <div class="text-gray-400 text-xs">
                                                @foreach($items as $it)
                                                    <div>{{ $it['title'] ?? 'Item' }} @if(($it['qty'] ?? 1) > 1) (x{{ $it['qty'] }}) @endif</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-white font-bold text-lg">€{{ number_format($purchase->amount, 2) }}</div>
                                        <div class="text-sm">
                                            @if($purchase->status === 'paid')
                                                <span class="bg-green-600 text-white px-2 py-1 rounded-full text-xs font-medium">Paid</span>
                                            @elseif($purchase->status === 'pending')
                                                <span class="bg-yellow-600 text-white px-2 py-1 rounded-full text-xs font-medium">Pending</span>
                                            @else
                                                <span class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-medium">{{ ucfirst($purchase->status) }}</span>
                                            @endif
                                        </div>
                                        <div class="text-gray-400 text-xs mt-1">{{ $purchase->days ?? 1 }} day(s)</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                No transactions yet
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Profile Section -->
            <div id="profile-section" class="section-content hidden">
                <div class="p-8">
                    <h3 class="text-white text-2xl font-bold mb-6">👤 Profile Settings</h3>
                    
                    <div class="bg-[#001f4c] rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-white font-medium mb-2">Name</label>
                                <input type="text" value="{{ $user->name }}" class="w-full bg-[#0a1428] border border-[#7fa7e1] rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" readonly>
                            </div>
                            <div>
                                <label class="block text-white font-medium mb-2">Email</label>
                                <input type="email" value="{{ $user->email }}" class="w-full bg-[#0a1428] border border-[#7fa7e1] rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" readonly>
                            </div>
                            <div>
                                <label class="block text-white font-medium mb-2">Member Since</label>
                                <input type="text" value="{{ $user->created_at->format('M j, Y') }}" class="w-full bg-[#0a1428] border border-[#7fa7e1] rounded-lg px-4 py-2 text-white" readonly>
                            </div>
                            <div>
                                <label class="block text-white font-medium mb-2">Total Projects</label>
                                <input type="text" value="{{ $active->count() + $finalized->count() }}" class="w-full bg-[#0a1428] border border-[#7fa7e1] rounded-lg px-4 py-2 text-white" readonly>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-[#7fa7e1]/20">
                            <button class="bg-[#2563eb] hover:bg-[#1d4ed8] text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                Edit Profile
                            </button>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors ml-4">
                                Change Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.section-content').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Remove active class from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active', 'bg-[#2563eb]');
        item.classList.add('text-gray-300');
    });
    
    // Show selected section
    document.getElementById(sectionName + '-section').classList.remove('hidden');
    
    // Add active class to clicked nav item
    event.target.closest('.nav-item').classList.add('active', 'bg-[#2563eb]');
    event.target.closest('.nav-item').classList.remove('text-gray-300');
    event.target.closest('.nav-item').classList.add('text-white');
}

function proceedToCheckout() {
    // This would integrate with your payment controller
    window.location.href = '/payment/create';
}
</script>

<style>
.nav-item.active {
    background-color: #2563eb !important;
    color: white !important;
}

.section-content {
    min-height: 100vh;
}

/* Smooth animations */
.hover\:scale-105:hover {
    transform: scale(1.05);
    transition: transform 0.5s ease-in-out;
}

.transition-all {
    transition: all 0.3s ease;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #0a1428;
}

::-webkit-scrollbar-thumb {
    background: #7fa7e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #9fb8e8;
}
</style>
@endsection
