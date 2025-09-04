<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pricing - Imhotion</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-sidebar-bg text-blue-100 p-8">
  <div class="max-w-6xl mx-auto">
    @foreach($categories as $category)
      <div class="mb-10" id="cat-{{ $category->slug }}">
        <h2 class="text-blue-50 text-center mb-3 text-2xl font-semibold">{{ $category->name }}</h2>
        @if($category->description)
          <p class="text-slate-400 text-center text-sm mb-6">{{ $category->description }}</p>
        @endif

        <div class="flex flex-wrap gap-4 justify-center">
          @foreach($category->items as $item)
            <div class="bg-gradient-to-br from-sidebar-active to-blue-900/50 p-5 rounded-2xl min-w-[260px] flex-1 max-w-[300px] shadow-2xl border border-blue-300/20">
              <div class="text-xl text-white mb-1 font-semibold">{{ $item->title }}</div>
              <div class="font-bold text-brand-primary-200 mb-2">
                €{{ number_format($item->price, 0) }} 
                <small class="text-blue-200">/{{ str_replace('per_','',$item->price_unit) }}</small>
              </div>
              @if($item->duration_years)
                <div class="text-slate-400 text-sm mb-3">Valid for {{ $item->duration_years }} {{ \Illuminate\Support\Str::plural('year', $item->duration_years) }}</div>
              @endif
              @if($item->discount_percent)
                <div class="text-slate-400 text-sm mb-3">{{ $item->discount_percent }}% Discount</div>
              @endif

              <ul class="list-none p-0 m-0 text-blue-200 space-y-1">
                <li class="{{ $item->has_gift_box ? 'text-brand-primary-200' : 'text-slate-600 line-through opacity-70' }}">
                  Gift Box
                </li>
                <li class="{{ $item->has_project_files ? 'text-brand-primary-200' : 'text-slate-600 line-through opacity-70' }}">
                  Project Files
                </li>
                <li class="{{ $item->has_weekends_included ? 'text-brand-primary-200' : 'text-slate-600 line-through opacity-70' }}">
                  Weekends Included
                </li>
              </ul>

              <a href="{{ route('purchase', $item->id) }}" 
                 class="inline-block mt-4 px-4 py-2 bg-gradient-to-r from-brand-primary to-brand-primary-200 text-white rounded-full no-underline font-medium hover:from-blue-600 hover:to-blue-400 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
                Purchase
              </a>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</body>
</html>