@extends('layouts.app')

@section('title', 'Đăng ký - CNW Clothing')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">Tạo tài khoản</h1>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Họ tên</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Số điện thoại <span class="text-muted">(tuỳ chọn)</span></label>
                            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="text" value="{{ old('phone') }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="address">Địa chỉ <span class="text-muted">(tuỳ chọn)</span></label>
                            <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" type="text" value="{{ old('address') }}">
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password">Mật khẩu</label>
                            <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>
                            <div class="form-text">Ít nhất 8 ký tự.</div>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                            <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
                        </div>
                    </div>
                    <button class="btn btn-dark w-100 mt-4" type="submit">Đăng ký</button>
                </form>
                <p class="text-center text-muted small mt-3 mb-0">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
