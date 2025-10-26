@extends('layouts.admin')

@section('title', 'Nuevo Producto')
@section('header', 'Crear Producto')

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

    <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.productos._form')
        <div style="margin-top:12px; display:flex; gap:8px;">
            <button type="submit">Guardar</button>
            <a href="{{ route('admin.productos.index') }}">Cancelar</a>
        </div>
    </form>
@endsection
