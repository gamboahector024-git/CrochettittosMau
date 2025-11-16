@extends('layouts.admin')

@section('title', 'Nuevo Usuario')
@section('header', 'Crear Usuario')

@section('content')

    {{-- Usamos la clase .form-container que ya está definida en admin.css --}}
    <div class="form-container">

        @if ($errors->any())
             {{-- La clase .alert-danger ya tiene estilos para la lista <ul> --}}
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

        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf
            
            {{-- 
              Esto incluye el archivo _form.blade.php que limpiamos antes.
              Como no le pasamos la variable $usuario, mostrará los campos vacíos.
            --}}
            @include('admin.usuarios._form')
            
            {{-- Usamos la clase .form-actions que ya está definida --}}
            <div class="form-actions">
                {{-- Añadimos las clases de botón --}}
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection