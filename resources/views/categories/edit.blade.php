@extends('layouts.app')

@section('title', 'Sửa danh mục - CNW Clothing')

@push('styles')
    <style>
        body { background: #f4f7fb; font-family: "Segoe UI", Arial, sans-serif; color: #243047; }
        .topbar { background: white; padding: 18px 0; border-bottom: 1px solid #e8edf4; }
        .brand { color: #2563eb; font-size: 22px; font-weight: 800; }
        .form-card { max-width: 760px; margin: 45px auto; padding: 32px; background: white; border: 1px solid #e9eef5; border-radius: 18px; box-shadow: 0 8px 30px #1e325a0a; }
        .form-label { font-weight: 650; margin-bottom: 8px; }
        .form-control { padding: 12px 14px; border-radius: 10px; border-color: #dce3ed; }
        .form-control:focus { border-color: #60a5fa; box-shadow: 0 0 0 .2rem #dbeafe; }
        .btn-primary { background: #2563eb; border: 0; border-radius: 9px; padding: 11px 18px; font-weight: 600; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-back { color: #475569; background: #f1f5f9; border-radius: 9px; padding: 11px 18px; text-decoration: none; font-weight: 600; }
    </style>
@endpush

@section('content')
<div class="container px-3">
    <div class="form-card">
        <h2 class="fw-bold mb-2">✎ Chỉnh sửa danh mục</h2>
        <p class="text-secondary mb-4">
            Cập nhật thông tin cho danh mục #{{ $category->id }}.
        </p>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $category->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control"
                       value="{{ old('slug', $category->slug) }}" required>
                <div class="form-text">Slug phải là duy nhất trong hệ thống.</div>
            </div>

            <div class="mb-4">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="4"
                          placeholder="Nhập mô tả...">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" name="is_active"
                       value="1" id="is_active"
                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Kích hoạt danh mục</label>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">✓ Lưu thay đổi</button>
                <a href="{{ route('categories.index') }}" class="btn-back">← Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection