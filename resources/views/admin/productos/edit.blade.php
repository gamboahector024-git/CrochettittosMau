@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('header', 'Editar Producto')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom:12px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.productos._form', ['producto' => $producto])
        <div style="margin-top:12px; display:flex; gap:8px;">
            <button type="submit">Actualizar</button>
            <a href="{{ route('admin.productos.index') }}">Cancelar</a>
        </div>
    </form>
@endsection
