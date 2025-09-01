<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, viewport-fit=cover"
/>
  <title>IMHOTION — Client Access</title>

  <style>
    :root{
      --bg1:#0b3bff;   /* deep brand blue */
      --bg2:#0a2fd4;   /* darker blend */
      --accent:#6aa1ff;
      --card:#0d1326d9; /* glass card */
      --text:#eaf1ff;
      --muted:#a9b7d9;
      --btn:#00c2ff;
      --btn2:#0066ff;
      --shadow:0 10px 30px rgba(0,0,0,.35);
    }

    *{ box-sizing:border-box }

    body{
      margin:0;
      color:var(--text);
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Inter, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
      background: radial-gradient(1200px 800px at 10% -10%, #1b49ff 0%, #0f33db 45%, #0b1f9f 100%) fixed;
      min-height:100dvh;
      display:flex;
      flex-direction:column;
    }

    /* ===== NAV ===== */
    header{
      position:sticky; top:0; z-index:50;
      background: linear-gradient(180deg, rgba(6,14,40,.65), rgba(6,14,40,.35), transparent);
      backdrop-filter: blur(10px);
      border-bottom:1px solid rgba(255,255,255,.06);
    }
    .nav{
      display:flex; align-items:center; gap:20px;
      padding:14px clamp(16px, 3vw, 32px);
      max-width:1200px; margin:0 auto;
    }
    .brand{
      display:flex; align-items:center; gap:12px; flex:0 0 auto;
      color:#fff; text-decoration:none; font-weight:800; letter-spacing:.06em;
    }
    .brand img{
      width:36px; height:36px; object-fit:cover; border-radius:8px; box-shadow: var(--shadow);
    }
    .brand span{ font-size:14px; opacity:.95 }

    .links{ margin-left:auto; margin-right:auto; display:flex; gap:24px }
    .links a{
      color:#ffffffd6; text-decoration:none; font-weight:600; font-size:14px;
    }
    .links a:hover{ color:#fff }

    .right{ display:flex; gap:10px; align-items:center }
    .btn, .btn-outline{
      appearance:none; border:0; cursor:pointer;
      padding:.65rem 1rem; border-radius:10px; font-weight:700; letter-spacing:.02em;
    }
    .btn{
      background: linear-gradient(160deg, var(--btn), var(--btn2)); color:#fff;
      box-shadow: 0 6px 18px rgba(0,102,255,.45);
    }
    .btn-outline{
      background: transparent; color:#fff; border:1px solid rgba(255,255,255,.25);
    }
    .btn:hover{ filter:brightness(1.05) }
    .btn-outline:hover{ background:rgba(255,255,255,.06) }

    /* ===== HERO ===== */
    .hero{
      display:grid; place-items:center;
      padding: clamp(40px, 8vw, 90px) 20px 40px;
      position:relative; overflow:hidden;
    }
    .center-wrap{ text-align:center; max-width:950px }
    .logo-big{
      width:min(420px, 70vw);
      aspect-ratio: 2 / 1;
      object-fit:cover; object-position:center;
      border-radius:20px;
      box-shadow: 0 18px 60px rgba(0,0,0,.45), inset 0 0 40px rgba(255,255,255,.12);
      display:block; margin: 6px auto 18px;
      animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
      0%,100%{ transform: translateY(0) }
      50%{ transform: translateY(-6px) }
    }
    h1{
      margin:.25rem 0 0;
      font-size: clamp(26px, 4.2vw, 44px); font-weight:900; letter-spacing:.02em;
    }
    p.sub{
      margin:.7rem auto 2rem; color: var(--muted);
      font-size: clamp(14px, 2.2vw, 18px); max-width: 720px;
    }

    /* ===== CARD (Login) ===== */
    .card{
      margin: 16px auto 0;
      width:min(430px, 92vw);
      background: linear-gradient(180deg, rgba(8,12,30,.75), rgba(8,12,30,.55));
      border: 1px solid rgba(255,255,255,.08);
      border-radius:16px; padding:22px 22px 20px;
      box-shadow: var(--shadow);
      backdrop-filter: blur(8px);
    }
    .card h3{
      margin:0 0 12px; text-align:center; font-size:18px; color:#e9f2ff; letter-spacing:.04em
    }
    form.login{
      display:grid; gap:12px;
    }
    .field{
      display:grid; gap:8px;
    }
    .field input{
      width:100%; padding:12px 14px;
      border-radius:12px; border:1px solid rgba(255,255,255,.18);
      background: rgba(255,255,255,.08);
      color:#fff; outline: none;
    }
    .field input::placeholder{ color:#cdd7ff99 }
    .remember{
      display:flex; gap:8px; align-items:center; color:#dbe5ffbb; font-size:14px;
    }
    .error{
      background:#2b1020; border:1px solid #ff4e86; color:#ffd1e1;
      padding:10px 12px; border-radius:10px; margin-bottom:8px; font-size:14px;
    }
    .helper{
      text-align:center; color:#b2c1ff; font-size:13px; margin-top:10px;
    }

    /* ===== Animated Wave into White Section ===== */
    .wave-wrap{
      position:relative; width:100%; margin-top:60px;
    }
    .waves{
      position:relative; width:100%; height:140px; overflow:hidden; line-height:0;
    }
    .waves svg{
      position:absolute; bottom:0; left:-25%;
      width:150%; height:140px;
      transform: translate3d(0,0,0);
      filter: drop-shadow(0 -6px 12px rgba(0,0,0,.12));
    }
    .wave-1{ animation: waveMove 14s linear infinite; opacity:.8 }
    .wave-2{ animation: waveMove 22s linear infinite; opacity:.55 }
    .wave-3{ animation: waveMove 36s linear infinite; opacity:.35 }
    @keyframes waveMove{
      0%{ transform: translateX(0) }
      100%{ transform: translateX(-33.333%) }
    }

    .white-section{
      background:#fff;
      color:#0f172a; padding: 60px 22px 80px;
      text-align:center;
    }
    .white-section h2{
      font-size: clamp(22px, 3vw, 32px); margin:0 0 12px
    }
    .white-section p{
      color:#475569; margin:0 auto; max-width:820px; font-size:16px
    }

    /* Small screens tweaks */
    @media (max-width:520px){
      .links{ display:none }
      .brand span{ display:none }
    }
  </style>
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <header>
    <div class="nav">
      <a class="brand" href="/">
        <img src="{{ asset('images/imhotion.jpg') }}" alt="IMHOTION">
        <span>IMHOTION</span>
      </a>

      <nav class="links" aria-label="Primary">
        <a href="/">Home</a>
        <a href="#about">About us</a>
        @guest
          <a href="{{ route('login') }}">Login</a>
        @endguest
        @auth
          <a href="{{ route('client') }}">Client</a>
        @endauth
      </nav>

      <div class="right">
        @auth
          <a href="{{ route('logout.get') }}" class="btn-outline" title="Logout">Logout</a>
        @endauth
      </div>
    </div>
  </header>

  <!-- ===== HERO + BIG LOGO + LOGIN CARD ===== -->
  <section class="hero">
    <div class="center-wrap">
      <img class="logo-big" src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion logo large" />
      <h1>Motion-first product visuals — made easy</h1>
      <p class="sub">High-end 3D design & motion for brands who care about detail and pace. Log in to your client area to track progress, deliveries, and timelines.</p>

      @guest
        <div class="card">
          <h3>Client sign in</h3>

          @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
          @endif

          <form method="POST" action="{{ route('login.post') }}" class="login" autocomplete="on">
            @csrf
            <label class="field">
              <input type="email" name="email" placeholder="Email address" required value="{{ old('email') }}">
            </label>
            <label class="field">
              <input type="password" name="password" placeholder="Password" required>
            </label>

            <label class="remember">
              <input type="checkbox" name="remember">
              <span>Remember me on this device</span>
            </label>

            <button class="btn" type="submit">Sign in</button>
            <div class="helper">Need access? Contact your Imhotion producer.</div>
          </form>
        </div>
      @else
        <div style="margin-top:6px;">
          <a class="btn" href="{{ route('client') }}">Go to Client Area</a>
        </div>
      @endguest
    </div>

    <!-- Waves transition -->
    <div class="wave-wrap" aria-hidden="true">
      <div class="waves">
        <svg class="wave-1" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M0,0 C300,100 900,-60 1200,40 L1200,120 L0,120 Z" fill="#0a2fd4"></path>
        </svg>
        <svg class="wave-2" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M0,20 C280,120 920,-40 1200,60 L1200,120 L0,120 Z" fill="#0b3bff"></path>
        </svg>
        <svg class="wave-3" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M0,40 C260,140 940,-20 1200,80 L1200,120 L0,120 Z" fill="#ffffff"></path>
        </svg>
      </div>
    </div>
  </section>

  <!-- ===== WHITE SECTION ===== -->
  <section class="white-section" id="about">
    <h2>Why Imhotion</h2>
    <p>
      We craft premium 3D visuals and motion for product launches and always-on content.
      Your client area centralizes briefs, deliveries, and approvals — so projects ship on time and look outstanding.
    </p>
  </section>

  <script>
    // Tiny hover parallax for the big logo (subtle)
    const logo = document.querySelector('.logo-big');
    if (logo){
      let tId;
      window.addEventListener('mousemove', e=>{
        cancelAnimationFrame(tId);
        tId = requestAnimationFrame(()=>{
          const { innerWidth:w, innerHeight:h } = window;
          const dx = (e.clientX - w/2) / (w/2);
          const dy = (e.clientY - h/2) / (h/2);
          logo.style.transform = `translateY(${ -6 + dy*4 }px) rotate(${ dx*1.2 }deg)`;
        });
      });
      window.addEventListener('mouseleave', ()=>logo.style.transform = '');
    }
  </script>
</body>
</html>
