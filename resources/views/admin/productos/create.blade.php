@extends('layouts.admin')

@section('title', 'Nuevo Producto')

@section('content')
    <div class="content-header">
        <h1>Crear Producto</h1>
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
        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @include('admin.productos._form', ['isEdit' => false, 'producto' => null])
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Producto</button>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection