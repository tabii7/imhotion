@extends('layouts.dashboard')

@section('title', 'Transactions')
@section('page-title', 'Transactions')

@section('content')
<!-- Transactions Page -->
<div class="rounded-xl p-5 text-white">
    <div class="text-white font-sans">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Transaction History</h1>
            <p class="text-sidebar-text">View all your purchases and payment history</p>
        </div>

        <!-- Transaction Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-sidebar-active border border-green-300 rounded-xl px-6 py-4 text-center">
                <div class="text-white text-sm font-medium mb-2">Total Spent</div>
                <div class="text-white text-2xl font-bold">€{{ number_format($userPurchases->sum('amount'), 2) }}</div>
            </div>
            
            <div class="bg-sidebar-active border border-blue-300 rounded-xl px-6 py-4 text-center">
                <div class="text-white text-sm font-medium mb-2">Total Transactions</div>
                <div class="text-white text-2xl font-bold">{{ $userPurchases->count() }}</div>
            </div>
            
            <div class="bg-sidebar-active border border-yellow-300 rounded-xl px-6 py-4 text-center">
                <div class="text-white text-sm font-medium mb-2">Hours Purchased</div>
                <div class="text-white text-2xl font-bold">{{ $userPurchases->sum('days') * 8 }}h</div>
            </div>
            
            <div class="bg-sidebar-active border border-purple-300 rounded-xl px-6 py-4 text-center">
                <div class="text-white text-sm font-medium mb-2">Successful Payments</div>
                <div class="text-white text-2xl font-bold">{{ $userPurchases->where('status', 'completed')->count() }}</div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-sidebar-active border border-gray-300 rounded-xl p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <input type="text" id="searchTransactions" placeholder="Search transactions..." 
                               class="w-full md:w-64 px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <select id="statusFilter" class="px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button onclick="exportTransactions('pdf')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </button>
                    <button onclick="exportTransactions('excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-sidebar-active border border-gray-300 rounded-xl overflow-hidden">
            @if($userPurchases->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Transaction ID</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($userPurchases as $purchase)
                                <tr class="hover:bg-gray-800 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-mono text-white">
                                            #{{ str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($purchase->pricingItem)
                                            <div class="text-sm font-medium text-white">
                                                {{ $purchase->pricingItem->title }}
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                {{ optional($purchase->pricingItem->category)->title ?? 'Development Hours' }}
                                            </div>
                                        @else
                                            @php $items = is_array($purchase->payment_data) ? $purchase->payment_data : json_decode($purchase->payment_data, true) ?? []; @endphp
                                            <div class="text-sm font-medium text-white">
                                                Multiple Items
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                @foreach($items as $it)
                                                    @php
                                                        $title = $it['title'] ?? ($it->title ?? 'Item');
                                                        $qty = $it['qty'] ?? ($it->quantity ?? 1);
                                                    @endphp
                                                    {{ $title }} @if($qty > 1) (x{{ $qty }}) @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-white">
                                            €{{ number_format($purchase->amount, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white">
                                            {{ ($purchase->days ?? 0) * 8 }} hours
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $purchase->days ?? 0 }} days
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($purchase->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Completed
                                            </span>
                                        @elseif($purchase->status === 'failed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Failed
                                            </span>
                                        @elseif($purchase->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ $purchase->created_at->format('M d, Y') }}
                                        <div class="text-xs">{{ $purchase->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <button onclick="viewTransactionDetails({{ $purchase->id }})" 
                                                    class="text-blue-400 hover:text-blue-300 transition-colors">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($purchase->status === 'completed')
                                                <button onclick="downloadReceipt({{ $purchase->id }})" 
                                                        class="text-green-400 hover:text-green-300 transition-colors">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="text-gray-400 mb-6">
                        <i class="fas fa-receipt text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">No transactions yet</h3>
                    <p class="text-gray-400 mb-8">Your purchase history will appear here once you make your first purchase.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button onclick="window.location.href='{{ route('dashboard.services') }}'" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Browse Services
                        </button>
                        <button onclick="window.location.href='{{ route('dashboard') }}'" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-home mr-2"></i>
                            Go to Dashboard
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($userPurchases->count() > 0)
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button class="px-3 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-3 py-2 text-sm bg-blue-600 text-white rounded">1</button>
                    <button class="px-3 py-2 text-sm text-gray-400 hover:text-white transition-colors">2</button>
                    <button class="px-3 py-2 text-sm text-gray-400 hover:text-white transition-colors">3</button>
                    <button class="px-3 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </nav>
            </div>
        @endif
    </div>
</div>

<!-- Transaction Details Modal -->
<div id="transactionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Transaction Details</h3>
                <button onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div id="transactionModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Search and Filter Functions
document.getElementById('searchTransactions').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (status === '') {
            row.style.display = '';
        } else {
            const statusCell = row.querySelector('td:nth-child(5)');
            const statusText = statusCell.textContent.toLowerCase();
            row.style.display = statusText.includes(status) ? '' : 'none';
        }
    });
});

// Transaction Details
function viewTransactionDetails(transactionId) {
    // Simulate loading transaction details
    document.getElementById('transactionModalContent').innerHTML = `
        <div class="space-y-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-3">Transaction Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Transaction ID</label>
                        <div class="font-mono text-sm">#${transactionId.toString().padStart(6, '0')}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Date</label>
                        <div class="text-sm">${new Date().toLocaleDateString()}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Amount</label>
                        <div class="text-sm font-semibold">€${(Math.random() * 2000 + 500).toFixed(2)}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status</label>
                        <div class="text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completed
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-3">Payment Details</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Payment Method</span>
                        <span class="text-sm">Credit Card (**** 4242)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Transaction Fee</span>
                        <span class="text-sm">€0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Hours</span>
                        <span class="text-sm font-semibold">${Math.floor(Math.random() * 100 + 20)} hours</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('transactionModal').classList.remove('hidden');
    document.getElementById('transactionModal').classList.add('flex');
}

function closeTransactionModal() {
    document.getElementById('transactionModal').classList.add('hidden');
    document.getElementById('transactionModal').classList.remove('flex');
}

// Export Functions
function exportTransactions(format) {
    alert(`Exporting transactions as ${format.toUpperCase()}...`);
}

function downloadReceipt(transactionId) {
    alert(`Downloading receipt for transaction #${transactionId}...`);
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.id === 'transactionModal') {
        closeTransactionModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        if (!document.getElementById('transactionModal').classList.contains('hidden')) {
            closeTransactionModal();
        }
    }
});
</script>
@endsection