<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #243047;
        }

        .topbar {
            background: #fff;
            padding: 18px 0;
            border-bottom: 1px solid #e8edf4;
        }

        .brand {
            color: #2563eb;
            font-size: 22px;
            font-weight: 800;
        }

        .page-container {
            max-width: 1250px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 750;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #7b8798;
            margin-bottom: 25px;
        }

        .stat-card, .content-card {
            background: white;
            border: 1px solid #e9eef5;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(30, 50, 90, .04);
        }

        .stat-card {
            padding: 22px;
            height: 100%;
        }

        .stat-label {
            color: #758197;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: 750;
        }

        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 13px;
            font-size: 23px;
            background: #eaf1ff;
        }

        .content-card {
            overflow: hidden;
            margin-top: 25px;
        }

        .card-heading {
            padding: 22px 24px;
            border-bottom: 1px solid #edf0f5;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            color: #65728a;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 16px;
            border-bottom: 1px solid #e9eef5;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 17px 16px;
            vertical-align: middle;
            border-color: #edf0f5;
        }

        .table tbody tr:hover {
            background: #f8fbff;
        }

        .category-name {
            font-weight: 650;
            color: #243047;
        }

        .category-id {
            background: #eef2f7;
            color: #596579;
            padding: 5px 10px;
            border-radius: 7px;
            font-size: 12px;
        }

        .slug {
            color: #64748b;
            font-size: 13px;
        }

        .status-active, .status-inactive {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 650;
        }

        .status-active {
            background: #dcfce7;
            color: #15803d;
        }

        .status-inactive {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-add {
            background: #2563eb;
            border: none;
            color: white;
            padding: 11px 17px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-add:hover {
            background: #1d4ed8;
            color: white;
        }

        .btn-edit, .btn-delete {
            border-radius: 8px;
            padding: 7px 11px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-edit {
            color: #2563eb;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            text-decoration: none;
        }

        .btn-edit:hover {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-delete {
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fee2e2;
        }

        .btn-delete:hover {
            background: #fee2e2;
            color: #b91c1c;
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px !important;
            color: #7b8798;
        }

        .alert {
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 23px;
            }

            .card-heading {
                padding: 18px;
            }
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
        <div class="brand">▦ Category Manager</div>
        <span class="text-secondary small">Hệ thống quản lý danh mục</span>
    </div>
</header>

<main class="page-container">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="page-title">Quản lý danh mục</h1>
            <p class="subtitle mb-0">
                Quản lý, thêm mới, chỉnh sửa và theo dõi danh mục.
            </p>
        </div>

        <a href="{{ route('categories.create') }}" class="btn-add">
            ＋ Thêm danh mục
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✓ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Tổng danh mục</div>
                        <div class="stat-number">{{ $categories->count() }}</div>
                    </div>
                    <div class="stat-icon">📁</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Đang hoạt động</div>
                        <div class="stat-number text-success">
                            {{ $categories->where('is_active', true)->count() }}
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#dcfce7">✓</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Đang ẩn</div>
                        <div class="stat-number text-danger">
                            {{ $categories->where('is_active', false)->count() }}
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#fee2e2">◉</div>
                </div>
            </div>
        </div>
    </div>

    <section class="content-card">
        <div class="card-heading d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-1 fw-bold">Danh sách danh mục</h5>
                <div class="text-secondary small">
                    Danh sách các danh mục hiện có trong hệ thống
                </div>
            </div>
            <span class="badge rounded-pill text-bg-light px-3 py-2">
                {{ $categories->count() }} danh mục
            </span>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:80px">Mã</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th style="min-width:160px">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <span class="category-id">#{{ $category->id }}</span>
                            </td>

                            <td>
                                <div class="category-name">{{ $category->name }}</div>
                            </td>

                            <td>
                                <span class="slug">{{ $category->slug }}</span>
                            </td>

                            <td>
                                {{ $category->description ?: 'Chưa có mô tả' }}
                            </td>

                            <td>
                                @if($category->is_active)
                                    <span class="status-active">● Hoạt động</span>
                                @else
                                    <span class="status-inactive">● Đang ẩn</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="btn-edit">
                                        ✎ Sửa
                                    </a>

                                    <form action="{{ route('categories.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục {{ $category->name }} không?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete">
                                            🗑 Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div style="font-size:42px">📂</div>
                                <h5 class="mt-3">Chưa có danh mục nào</h5>
                                <p>Hãy thêm danh mục đầu tiên để bắt đầu quản lý.</p>
                                <a href="{{ route('categories.create') }}" class="btn-add">
                                    ＋ Thêm danh mục ngay
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="text-center text-secondary small mt-4">
        Trang quản lý danh mục · Laravel
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>