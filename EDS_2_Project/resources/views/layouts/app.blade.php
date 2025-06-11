<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Stocks') }} - @yield('title', 'Dashboard')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
        background: #0055e8;
        background: linear-gradient(180deg, rgba(0, 85, 232, 1) 0%, rgba(67, 224, 133, 1) 50%, rgba(210, 255, 105, 1) 100%);
        background-attachment: fixed;
        }
        .app-container {
            max-width: 1400px;
        }
        .main-content {
            padding: 30px 0;
        }
        footer {
            margin-top: 40px;
            padding: 20px 0;
            border-top: 1px solid #dee2e6;
        }
        .navbar-brand {
            font-weight: 600;
        }
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container app-container">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Stocks') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                            @php
                                $homeRoute = match(Auth::user()->role) {
                                    'administrador' => route('admin.dashboard'),
                                    'funcionario' => route('funcionario.dashboard'),
                                    'gestor' => route('gestor.dashboard'),
                                    default => url('/'),
                                };
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ $homeRoute }}">
                                    <i class="bi bi-house-door me-1"></i> Inicio
                                </a>
                            </li>
                        @endauth
                    </ul>
                    <form class="d-flex" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>


        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h2 text-light">@yield('title', 'Dashboard')</h1>
                <div>
                    @yield('header-buttons')
                </div>
            </div>
            @hasSection('subtitle')
                <p class="lead text-muted mt-2">@yield('subtitle')</p>
            @endif
        </div>

 
        <div class="alerts-container">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please check the following issues:
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>


        <main class="main-content">
            @yield('content')
        </main>

        <footer class="text-center text-muted">
            <div class="bg-light">
                <div class="container">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                    <p class="small">App Version: {{ app()->version() }}</p>
                </div>
             </div>    
            
        </footer>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>