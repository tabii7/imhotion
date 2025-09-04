@extends('layouts.dashboard')

@section('title', 'Transactions')
@section('page-title', 'Transactions')

@section('content')
    <!-- Mini Cart Component -->

    <!-- Transactions Section -->
    <div style="background: #0a1428; border-radius: 12px; padding: 20px; color: #ffffff;">
        <div class="transactions-section">
            <h2 style="color: #ffffff; font-size: 20px; font-weight: 600; margin-bottom: 25px; font-family: var(--font-sans)">
                Transaction History
            </h2>

            @if($userPurchases->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #001f4c; border: 1px solid #7fa7e1;">
                                <th style="padding: 12px; text-align: left; color: #ffffff; font-size: 13px; font-weight: 600; border: 1px solid #7fa7e1;">Transaction ID</th>
                                <th style="padding: 12px; text-align: left; color: #ffffff; font-size: 13px; font-weight: 600; border: 1px solid #7fa7e1;">Service</th>
                                <th style="padding: 12px; text-align: left; color: #ffffff; font-size: 13px; font-weight: 600; border: 1px solid #7fa7e1;">Amount</th>
                                <th style="padding: 12px; text-align: left; color: #ffffff; font-size: 13px; font-weight: 600; border: 1px solid #7fa7e1;">Days</th>
                                <th style="padding: 12px; text-align: left; color: #ffffff; font-size: 13px; font-weight: 600; border: 1px solid #7fa7e1;">Status</th>
                                <th style="padding: 12px; text-align: left; color: #ffffff; font-size: 13px; font-weight: 600; border: 1px solid #7fa7e1;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userPurchases as $purchase)
                                <tr style="background: #121e2f; border: 1px solid #7fa7e1;" onmouseover="this.style.background='#1a2a40'" onmouseout="this.style.background='#121e2f'">
                                    <td style="padding: 12px; color: #ffffff; font-size: 14px; border: 1px solid #7fa7e1; font-family: monospace;">
                                        #{{ str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #7fa7e1;">
                                        @if($purchase->pricingItem)
                                            <div style="color: #ffffff; font-size: 14px; font-weight: 600; margin-bottom: 2px;">
                                                {{ $purchase->pricingItem->title }}
                                            </div>
                                            <div style="color: #8fa8cc; font-size: 12px;">
                                                {{ optional($purchase->pricingItem->category)->title ?? '' }}
                                            </div>
                                        @else
                                            {{-- Multi-item purchase or legacy purchase without a single pricing_item_id --}}
                                            @php $items = is_array($purchase->payment_data) ? $purchase->payment_data : json_decode($purchase->payment_data, true) ?? []; @endphp
                                            <div style="color: #ffffff; font-size: 14px; font-weight: 600; margin-bottom: 6px;">
                                                Multiple items
                                            </div>
                                            <div style="color: #8fa8cc; font-size: 12px;">
                                                @foreach($items as $it)
                                                    @php
                                                        $title = $it['title'] ?? ($it->title ?? 'Item');
                                                        $qty = $it['qty'] ?? ($it->quantity ?? 1);
                                                    @endphp
                                                    <div>{{ $title }} @if($qty > 1) (x{{ $qty }}) @endif</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; color: #ffffff; font-size: 14px; font-weight: 600; border: 1px solid #7fa7e1;">
                                        €{{ number_format($purchase->amount, 2) }}
                                    </td>
                                    <td style="padding: 12px; color: #ffffff; font-size: 14px; font-weight: 600; border: 1px solid #7fa7e1;">
                                        {{ $purchase->days ?? 0 }} days
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #7fa7e1;">
                                        @if($purchase->status === 'completed')
                                            <span style="background: #22c55e; color: #ffffff; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                Completed
                                            </span>
                                        @elseif($purchase->status === 'failed')
                                            <span style="background: #dc2626; color: #ffffff; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                Failed
                                            </span>
                                        @else
                                            <span style="background: #f59e0b; color: #ffffff; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; color: #8fa8cc; font-size: 14px; border: 1px solid #7fa7e1;">
                                        {{ $purchase->created_at->format('M d, Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 40px;">
                    <div style="color: #8fa8cc; margin-bottom: 20px;">
                        <svg style="width: 48px; height: 48px; margin: 0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 style="color: #ffffff; font-size: 18px; font-weight: 600; margin-bottom: 8px;">
                        No transactions yet
                    </h3>
                    <p style="color: #8fa8cc; font-size: 14px;">
                        Your purchase history will appear here once you make your first order.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
