<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
</head>
<body style="font-family:system-ui;margin:2rem;max-width:420px">
  <h1>Login</h1>

  @if ($errors->any())
    <div style="padding:.75rem;border:1px solid #f99;background:#fee;margin-bottom:1rem">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('login.post') }}" style="display:grid;gap:.75rem">
    @csrf
    <label>Email
      <input name="email" type="email" value="{{ old('email') }}" required style="width:100%">
    </label>
    <label>Password
      <input name="password" type="password" required style="width:100%">
    </label>
    <label style="display:flex;gap:.5rem;align-items:center">
      <input type="checkbox" name="remember"> <span>Remember me</span>
    </label>
    <button type="submit">Sign in</button>
  </form>
</body>
</html>
