@php
/**
 * Row details for a PricingCategory record.
 * Expects $record to be available.
 */
$category = $record;
$items = $category->items()->orderBy('sort')->get();
@endphp

<div class="p-4">
  <h3 class="text-lg font-semibold mb-3">Items</h3>
  <table class="w-full table-auto text-left text-sm">
    <thead>
      <tr class="text-xs text-gray-500">
        <th class="pb-2">Title</th>
        <th class="pb-2">Price</th>
        <th class="pb-2">Unit</th>
        <th class="pb-2">Gift</th>
        <th class="pb-2">Files</th>
        <th class="pb-2">Weekends</th>
        <th class="pb-2">Discount</th>
        <th class="pb-2">Active</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $item)
        <tr class="border-t">
          <td class="py-2">{{ $item->title }}</td>
          <td class="py-2">€{{ number_format($item->price, 2) }}</td>
          <td class="py-2">{{ $item->price_unit }}</td>
          <td class="py-2">{!! $item->has_gift_box ? '<span class="text-green-500">✓</span>' : '<span class="text-red-400">✕</span>' !!}</td>
          <td class="py-2">{!! $item->has_project_files ? '<span class="text-green-500">✓</span>' : '<span class="text-red-400">✕</span>' !!}</td>
          <td class="py-2">{!! $item->has_weekends_included ? '<span class="text-green-500">✓</span>' : '<span class="text-red-400">✕</span>' !!}</td>
          <td class="py-2">{{ $item->discount_percent ? $item->discount_percent.'%' : '-' }}</td>
          <td class="py-2">{!! $item->active ? '<span class="text-green-500">✓</span>' : '<span class="text-red-400">✕</span>' !!}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
