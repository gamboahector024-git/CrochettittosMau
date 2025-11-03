@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('header', 'Editar Usuario')

@section('content')

    {{-- El .form-container es idéntico a .card, pero más semántico --}}
    <div class="form-container">
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

        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Aquí se incluyen los campos del formulario --}}
            @include('admin.usuarios._form', ['usuario' => $usuario])

            {{-- Contenedor para los botones de acción --}}
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection