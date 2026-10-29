@extends('layouts.app')
@section('title', 'Thêm sản phẩm - CNW Clothing')
@section('content')
<h1 class="h3 mb-4">Thêm sản phẩm</h1>
<form method="POST" action="{{ route('products.store') }}">
    @csrf
    @include('products._form', ['product' => null])
</form>
@endsection
