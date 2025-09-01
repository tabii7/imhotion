<div style="font-family: system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;">
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;">
    <div>
      <h2 style="margin:0 0 8px;font-size:18px;">{{ $record->title }}</h2>
      <div style="color:#6f7fa8;margin-bottom:10px;">Client: {{ $record->user?->email }}</div>

      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:14px;">
        <div><strong>Status:</strong> {{ $record->status_label }}</div>
        <div><strong>Start:</strong> {{ $record->start_date?->format('Y-m-d') ?? '—' }}</div>
        <div><strong>End:</strong> {{ $record->end_date?->format('Y-m-d') ?? '—' }}</div>
        <div><strong>Delivery:</strong> {{ $record->delivery_date?->format('Y-m-d') ?? '—' }}</div>
        <div><strong>Days used:</strong> {{ $record->days_used }}</div>
        <div><strong>Day budget:</strong> {{ $record->day_budget ? number_format($record->day_budget, 2) : '—' }}</div>
      </div>

      <div style="padding:10px;border:1px solid #24335a;border-radius:10px;background:#0f1a33;">
        <div style="color:#a8b4d9;margin-bottom:6px;">Notes</div>
        <div>{{ $record->notes ?: 'No notes yet.' }}</div>
      </div>
    </div>

    <div>
      <div style="color:#a8b4d9;margin-bottom:6px;">Add note / file (below)</div>
      <small style="color:#6f7fa8;">Fill the form fields and click “Add note / file”.</small>
    </div>
  </div>

  <div style="margin-top:18px;">
    <div style="color:#a8b4d9;margin-bottom:8px;">Previous uploads & notes</div>
    @php $items = $record->deliveries; @endphp
    @if($items->isEmpty())
      <div style="color:#6f7fa8;">No deliveries yet.</div>
    @else
      <ul style="list-style:none;margin:0;padding:0;display:grid;gap:10px;">
        @foreach($items as $d)
          <li style="padding:10px;border:1px solid #24335a;border-radius:10px;background:#0f1a33;">
            <div style="display:flex;justify-content:space-between;gap:10px;">
              <div>
                <div style="font-weight:600;">{{ $d->title ?: 'Untitled' }}</div>
                @if($d->note)
                  <div style="margin-top:6px;">{{ $d->note }}</div>
                @endif
                <div style="color:#6f7fa8;margin-top:6px;">{{ $d->created_at->format('Y-m-d H:i') }}</div>
              </div>
              <div style="text-align:right;min-width:160px;">
                @if($d->file_path)
                  @php
                    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($d->file_path);
                  @endphp
                  <a href="{{ $url }}" target="_blank" style="color:#a8c2ff;text-decoration:none;">Download file</a>
                @else
                  <span style="color:#6f7fa8;">No file</span>
                @endif
              </div>
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</div>
