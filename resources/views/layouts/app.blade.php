<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('scripts')
    </head>
    <body class="font-sans antialiased">
 <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
  <!-- Sidebar -->
  <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
    <!-- Isi sidebar -->
    @include('layouts.sidebar')
  </aside>

  <!-- Konten utama -->
  <div class="flex-1">
    <!-- Navbar di atas konten -->
    @include('layouts.navigation')

    <!-- Header halaman -->
    @if (isset($header))
      <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endif

    <!-- Konten halaman -->
<main class="flex-1 bg-gray-100 dark:bg-gray-900">
    {{ $slot }}
</main>

  </div>
</div>

    </body>
</html>
