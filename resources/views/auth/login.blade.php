@extends('layouts.app')

@section('title', 'Đăng nhập - CNW Clothing')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">Đăng nhập tài khoản</h1>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Mật khẩu</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <button class="btn btn-dark w-100" type="submit">Đăng nhập</button>
                </form>
                <p class="text-center text-muted small mt-3 mb-0">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
