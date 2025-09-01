<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pricing - Imhotion</title>
  <style>
    body { font-family: 'BrittiSans', system-ui, sans-serif; background: #061226; color: #dbeafe; padding: 2rem; }
    .container { max-width:1100px; margin:0 auto; }
    .category { margin-bottom: 2.5rem; }
    .category h2 { color: #f2f7ff; text-align:center; margin-bottom:0.75rem; }
    .items { display:flex; flex-wrap:wrap; gap:1rem; justify-content:center; }
    .card { background: linear-gradient(135deg,#001f4c 0%, #061b3a 100%); padding:1.25rem; border-radius:14px; min-width:260px; flex:1 1 300px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border:1px solid rgba(153,194,255,0.12); }
    .title { font-size:1.25rem; color:#ffffff; margin-bottom:0.25rem; }
    .price { font-weight:700; color:#99c2ff; margin-bottom:0.5rem; }
    .meta { color:#9fb3d9; font-size:0.9rem; margin-bottom:0.75rem; }
    .features { list-style:none; padding:0; margin:0; color:#bcd6f8; }
    .features li.no { color:#5b6b80; text-decoration:line-through; opacity:0.7; }
    .features li.yes { color:#99c2ff; }
    .btn { display:inline-block; margin-top:1rem; padding:0.6rem 1rem; background:linear-gradient(135deg,#0066ff 0%,#99c2ff 100%); color:white; border-radius:999px; text-decoration:none; }
    @media (max-width:640px){ .items{flex-direction:column; align-items:center} .card{width:100%;} }
  </style>
</head>
<body>
  <div class="container">
    @foreach($categories as $category)
      <div class="category" id="cat-{{ $category->slug }}">
        <h2>{{ $category->name }}</h2>
        @if($category->description)
          <p class="meta" style="text-align:center;">{{ $category->description }}</p>
        @endif

        <div class="items">
          @foreach($category->items as $item)
            <div class="card">
              <div class="title">{{ $item->title }}</div>
              <div class="price">€{{ number_format($item->price, 0) }} <small style="color:#cde1ff">/{{ str_replace('per_','',$item->price_unit) }}</small></div>
              @if($item->duration_years)
                <div class="meta">Valid for {{ $item->duration_years }} {{ \Illuminate\Support\Str::plural('year', $item->duration_years) }}</div>
              @endif
              @if($item->discount_percent)
                <div class="meta">{{ $item->discount_percent }}% Discount</div>
              @endif

              <ul class="features">
                <li class="{{ $item->has_gift_box ? 'yes' : 'no' }}">Gift Box</li>
                <li class="{{ $item->has_project_files ? 'yes' : 'no' }}">Project Files</li>
                <li class="{{ $item->has_weekends_included ? 'yes' : 'no' }}">Weekends Included</li>
              </ul>

              @if($item->note)
                <div class="meta">{{ $item->note }}</div>
              @endif

              <a class="btn" href="mailto:info@imhotion.com?subject=Inquiry about {{ urlencode($item->title) }}">Get {{ $item->title }}</a>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</body>
</html>
