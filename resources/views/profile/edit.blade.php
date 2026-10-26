@extends('layouts.app')

@section('title', 'Tài khoản - CNW Clothing')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4">Quản lý tài khoản</h1>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Họ tên</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Số điện thoại</label>
                            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="address">Địa chỉ</label>
                            <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $user->address) }}">
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password">Mật khẩu mới</label>
                            <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password">
                            <div class="form-text">Để trống nếu không muốn đổi.</div>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password_confirmation">Xác nhận mật khẩu mới</label>
                            <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">
                        </div>
                    </div>
                    <button class="btn btn-dark mt-4" type="submit">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
