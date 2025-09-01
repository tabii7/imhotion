<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <img src="{{ asset('images/imhotion.jpg') }}" alt="logo" class="h-8 w-8 rounded"/>
              <div class="text-lg font-semibold">{{ config('app.name', 'Imhotion Admin') }}</div>
            </div>
            <div>
              <a href="/admin" class="text-sm text-indigo-600 hover:underline">Open Admin</a>
            </div>
          </div>
        </div>
      </header>

      <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          @yield('content')
        </div>
      </main>
    </div>
  </body>
</html>
