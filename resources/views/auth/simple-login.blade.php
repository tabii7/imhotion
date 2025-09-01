<!doctype html>
<html>
<head>
  <!--
    Copilot / AI Agent Instructions (summary)
    - Primary instruction files: `.github/copilot-instructions.md` and `.github/instructions/` (UI-refactoring)
    - Branding: logo at `public/images/imhotion.jpg`, fonts in `public/fonts/` (BrittiSans family)
    - Colors: primary #0066ff, light #99c2ff, lighter #f2f7ff, dark #001f4c
    - UI guidance: minimalistic centered layout; use Filament components for admin UIs; prefer slideOver modals and auto-save on blur/enter
    - When editing this view: keep layout centered, use `asset()` for static paths and preserve accessibility attributes.
  -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - Imhotion</title>
  <style>
    @font-face {
      font-family: 'BrittiSans';
      src: url('/fonts/BrittiSansRegular.ttf') format('truetype');
      font-weight: 400;
      font-display: swap;
    }
    @font-face {
      font-family: 'BrittiSans';
      src: url('/fonts/BrittiSansMedium.ttf') format('truetype');
      font-weight: 500;
      font-display: swap;
    }
    @font-face {
      font-family: 'BrittiSans';
      src: url('/fonts/BrittiSansSemibold.ttf') format('truetype');
      font-weight: 600;
      font-display: swap;
    }
    @font-face {
      font-family: 'BrittiSans';
      src: url('/fonts/BrittiSansBold.ttf') format('truetype');
      font-weight: 700;
      font-display: swap;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'BrittiSans', system-ui, -apple-system, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 3rem 2rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
      text-align: center;
    }

    .logo {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      margin: 0 auto 1.5rem;
      display: block;
      object-fit: cover;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .title {
      font-size: 1.75rem;
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 0.5rem;
    }

    .subtitle {
      color: #6b7280;
      margin-bottom: 2rem;
      font-weight: 400;
    }

    .form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      text-align: left;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-label {
      font-weight: 500;
      color: #374151;
      font-size: 0.875rem;
    }

    .form-input {
      width: 100%;
      padding: 0.875rem 1rem;
      border: 1.5px solid #e5e7eb;
      border-radius: 12px;
      font-size: 1rem;
      font-family: inherit;
      transition: all 0.2s ease;
      background: white;
    }

    .form-input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .checkbox {
      width: 1.125rem;
      height: 1.125rem;
      accent-color: #667eea;
    }

    .checkbox-label {
      font-size: 0.875rem;
      color: #374151;
      font-weight: 400;
    }

    .submit-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 1rem;
      font-size: 1rem;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.2s ease;
      margin-top: 0.5rem;
    }

    .submit-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .submit-btn:active {
      transform: translateY(0);
    }

    .error-message {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      color: #dc2626;
      padding: 1rem;
      border-radius: 12px;
      margin-bottom: 1.5rem;
      font-size: 0.875rem;
      text-align: center;
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 2rem 1.5rem;
        margin: 1rem;
      }

      .title {
        font-size: 1.5rem;
      }

      .logo {
        width: 70px;
        height: 70px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
  <img src="<?php echo e(asset('images/imhotion.jpg')); ?>" alt="Imhotion Logo" class="logo">
    <h1 class="title">Welcome Back</h1>
    <p class="subtitle">Sign in to your account</p>

    @if ($errors->any())
      <div class="error-message">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="form">
      @csrf
      
      <div class="form-group">
        <label class="form-label" for="email">Email Address</label>
        <input 
          id="email"
          name="email" 
          type="email" 
          value="{{ old('email') }}" 
          required 
          class="form-input"
          placeholder="Enter your email"
        >
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input 
          id="password"
          name="password" 
          type="password" 
          required 
          class="form-input"
          placeholder="Enter your password"
        >
      </div>

      <div class="checkbox-group">
        <input type="checkbox" name="remember" id="remember" class="checkbox">
        <label for="remember" class="checkbox-label">Remember me</label>
      </div>

      <button type="submit" class="submit-btn">Sign In</button>
    </form>
  </div>
</body>
</html>
