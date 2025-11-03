@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
    <div class="content-header">
        <h1>Editar Producto</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="form-container">
        <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @include('admin.productos._form', ['isEdit' => true, 'producto' => $producto])
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection