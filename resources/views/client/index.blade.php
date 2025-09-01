<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Client — Projects</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root { --bg:#0b1320; --panel:#0f1a33; --muted:#6f7fa8; --text:#e6ecff; --brand:#3d63ff; --ok:#19c37d; --warn:#ffb020; --bad:#ff5c74; --line:#24335a; }
    * { box-sizing: border-box; }
    body { margin:0; font-family: system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; background:var(--bg); color:var(--text); }
    .wrap { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
    .grid { display:grid; grid-template-columns: repeat(3, 1fr); gap:12px; margin-bottom: 18px; }
    .card { background:var(--panel); border:1px solid var(--line); border-radius:14px; padding:16px; }
    .card h3 { margin:0 0 6px; font-size:14px; color:#a8b4d9; font-weight:600; letter-spacing:.3px; }
    .card .big { font-size:26px; font-weight:700; }
    .btn { border:0; background: var(--brand); color:#fff; padding:8px 12px; border-radius:10px; cursor:pointer; font-weight:600; }
    .btn:disabled { opacity:.6; cursor:default; }
    table { width:100%; border-collapse: collapse; }
    th, td { padding:12px 10px; border-bottom:1px solid var(--line); vertical-align: top; }
    th { text-align:left; color:#a8b4d9; font-weight:600; }
    tr:hover .title { text-decoration: underline; }
    .status { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; font-weight:700; }
    .s-in_progress { background:rgba(61,99,255,.18); color:#a8c2ff; border:1px solid rgba(61,99,255,.35); }
    .s-completed   { background:rgba(25,195,125,.15); color:#b3f1d9; border:1px solid rgba(25,195,125,.35); }
    .s-on_hold     { background:rgba(255,176,32,.12); color:#ffe2b3; border:1px solid rgba(255,176,32,.3); }
    .s-finalized   { background:rgba(168,168,168,.15); color:#e0e0e0; border:1px solid rgba(168,168,168,.3); }
    .s-cancelled   { background:rgba(255,92,116,.12); color:#ffd4db; border:1px solid rgba(255,92,116,.3); }
    .plus { width:32px; height:32px; border-radius:8px; border:1px solid var(--line); background:#0b1733; color:#a8c2ff; font-size:18px; line-height:30px; text-align:center; cursor:pointer; }
    .plus[aria-expanded="true"] { background: #11224f; color:#fff; }
    .row-details { background:#0c1733; }
    .muted { color:var(--muted); }
    .right { text-align:right; }
    .topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
    .title { font-weight:700; }
    @media (max-width: 860px){
      .grid { grid-template-columns: 1fr; }
      .right { text-align:left; }
      .hide-sm { display:none; }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="topbar">
      <h1 style="font-size:20px; margin:0;">Welcome, {{ e($user->full_name ?? $user->name ?? $user->email) }}</h1>
      <a class="btn" href="/logout">Logout</a>
    </div>

    <div class="grid">
      <div class="card">
        <h3>Active projects</h3>
        <div class="big">{{ $counts['active'] }}</div>
      </div>
      <div class="card">
        <h3>Balance (days)</h3>
        <div class="big">{{ $counts['balance'] }}</div>
        <div class="muted" style="margin-top:6px;">Top-up soon (Mollie later)</div>
      </div>
      <div class="card">
        <h3>Finalized/Cancelled</h3>
        <div class="big">{{ $counts['finalized'] }}</div>
      </div>
    </div>

    <div class="card" style="margin-bottom:16px;">
      <h3 style="margin-bottom:8px;">Active</h3>
      <table aria-label="Active projects">
        <thead>
          <tr>
            <th style="width:42px;"></th>
            <th>Project</th>
            <th class="hide-sm">Delivery</th>
            <th>Status</th>
            <th class="right hide-sm">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($active as $p)
            <tr id="row-{{ $p->id }}">
              <td><button class="plus" aria-expanded="false" aria-controls="details-{{ $p->id }}" onclick="toggleRow({{ $p->id }});">+</button></td>
              <td class="title">{{ e($p->title) }}</td>
              <td class="hide-sm">{{ $p->delivery_date?->format('Y-m-d') ?? '—' }}</td>
              <td><span class="status s-{{ $p->status }}">{{ $p->status_label }}</span></td>
              <td class="right hide-sm">
                <button class="btn" onclick="alert('Add days flow later');">+ Add days</button>
              </td>
            </tr>
            <tr id="details-{{ $p->id }}" class="row-details" style="display:none;">
              <td></td>
              <td colspan="4">
                <div style="display:grid; grid-template-columns:2fr 1fr; gap:16px;">
                  <div>
                    <div class="muted" style="margin-bottom:6px;">Project details</div>
                    <div><strong>Notes:</strong> {{ $p->notes ? e($p->notes) : 'No notes yet.' }}</div>
                    <div style="margin-top:8px;"><strong>Day budget:</strong> {{ $p->day_budget ? number_format($p->day_budget,2) : '—' }}</div>
                    <div style="margin-top:8px;"><strong>Days used:</strong> {{ $p->days_used }}</div>
                  </div>
                  <div>
                    <div class="muted" style="margin-bottom:6px;">Meta</div>
                    <div><strong>Created:</strong> {{ $p->created_at->format('Y-m-d H:i') }}</div>
                    <div style="margin-top:6px;"><strong>Updated:</strong> {{ $p->updated_at->format('Y-m-d H:i') }}</div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="muted">No active projects yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card">
      <h3 style="margin-bottom:8px;">Finalized / Cancelled</h3>
      <table aria-label="Finalized projects">
        <thead>
          <tr>
            <th style="width:42px;"></th>
            <th>Project</th>
            <th class="hide-sm">Delivery</th>
            <th>Status</th>
            <th class="right hide-sm">—</th>
          </tr>
        </thead>
        <tbody>
          @forelse($finalized as $p)
            <tr id="row-f-{{ $p->id }}">
              <td><button class="plus" aria-expanded="false" aria-controls="details-f-{{ $p->id }}" onclick="toggleRow('f-{{ $p->id }}');">+</button></td>
              <td class="title">{{ e($p->title) }}</td>
              <td class="hide-sm">{{ $p->delivery_date?->format('Y-m-d') ?? '—' }}</td>
              <td><span class="status s-{{ $p->status }}">{{ $p->status_label }}</span></td>
              <td class="right hide-sm">—</td>
            </tr>
            <tr id="details-f-{{ $p->id }}" class="row-details" style="display:none;">
              <td></td>
              <td colspan="4">
                <div class="muted">This project is {{ strtolower($p->status_label) }}. Details remain read-only.</div>
                <div style="margin-top:8px;"><strong>Notes:</strong> {{ $p->notes ? e($p->notes) : 'No notes.' }}</div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="muted">No finalized projects yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

  <script>
    // Only one open at a time; expand/collapse with the "+" button
    function toggleRow(id) {
      // close others
      document.querySelectorAll('[id^="details-"]').forEach(el => {
        if (el.id !== 'details-' + id) el.style.display = 'none';
      });
      document.querySelectorAll('.plus').forEach(btn => {
        if (btn.getAttribute('aria-controls') !== 'details-' + id) btn.setAttribute('aria-expanded', 'false');
      });

      const details = document.getElementById('details-' + id);
      const btn = document.querySelector('.plus[aria-controls="details-' + id + '"]');
      const isOpen = details && details.style.display !== 'none';

      if (!details) return;

      if (isOpen) {
        details.style.display = 'none';
        if (btn) btn.setAttribute('aria-expanded', 'false');
      } else {
        details.style.display = '';
        if (btn) btn.setAttribute('aria-expanded', 'true');
      }
    }
  </script>
</body>
</html>
