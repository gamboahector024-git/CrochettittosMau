@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
    {{-- Encabezado con estilo Playfair --}}
    <div class="content-header" style="margin-bottom: 30px;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Editar Usuario
        </h1>
        <p style="color: var(--text-muted);">Editando perfil de: <strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong></p>
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

    {{-- Usamos .card para el efecto Glassmorphism consistente --}}
    <div class="card">
        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.usuarios._form', ['usuario' => $usuario, 'isEdit' => true])

            <div class="form-actions" style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 20px; justify-content: flex-end;">
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary" style="margin-right: 10px;">Cancelar</a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
@endsection