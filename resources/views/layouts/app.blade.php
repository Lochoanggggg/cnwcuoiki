<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CNW Clothing')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">CNW Clothing</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                @auth
                    @can('customer')
                        <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                    @endcan
                    @can('manage-categories')
                        <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Quản lý danh mục</a></li>
                    @endcan
                    @can('manage-store')
                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Sản phẩm</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Đơn hàng</a></li>
                    @endcan
                    @can('customer')
                        <li class="nav-item"><a class="nav-link" href="{{ route('customer.orders.index') }}">Đơn hàng của tôi</a></li>
                    @endcan
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}">Xin chào, {{ auth()->user()->name }}</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm" type="submit">Đăng xuất</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Đăng nhập</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
