@extends('layouts.admin')

@section('title', 'Nueva Promoción')

@section('content')
    <div class="content-header">
        <h1>Crear Nueva Promoción</h1>
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
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="form-container">
        <form action="{{ route('admin.promociones.store') }}" method="POST">
            @csrf
            
            @include('admin.promociones._form', ['isEdit' => false, 'promocion' => null, 'producto' => $producto])
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Promoción</button>
                <a href="{{ route('admin.promociones.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection