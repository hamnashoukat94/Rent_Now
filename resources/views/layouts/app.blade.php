<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RentNow') | RentNow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f6fb;
            color: #1d1d1d;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: .05em;
        }
        .btn-pill {
            border-radius: 999px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">RentNow</a>
            <div class="d-flex gap-2 align-items-center">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm btn-pill">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm btn-pill">Signup</a>
                @else
                    <span class="text-secondary me-2">Hi, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm btn-pill">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    <main class="py-5">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
