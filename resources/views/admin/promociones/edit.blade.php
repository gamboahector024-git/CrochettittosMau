@extends('layouts.admin')

@section('title', 'Editar Promoción')

@section('content')
    <div class="content-header">
        <h1>Editar Promoción</h1>
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
        <form action="{{ route('admin.promociones.update', $promocion) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.promociones._form', ['isEdit' => true, 'promocion' => $promocion])
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Promoción</button>
                <a href="{{ route('admin.promociones.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection 