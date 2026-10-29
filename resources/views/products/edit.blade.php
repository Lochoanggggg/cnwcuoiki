@extends('layouts.app')
@section('title', 'Sửa sản phẩm - CNW Clothing')
@section('content')
<h1 class="h3 mb-4">Sửa sản phẩm</h1>
<form method="POST" action="{{ route('products.update', $product) }}">
    @csrf @method('PUT')
    @include('products._form')
</form>
@endsection
