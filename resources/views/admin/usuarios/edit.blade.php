@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('header', 'Editar Usuario')

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

    <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.usuarios._form', ['usuario' => $usuario])
        <div style="margin-top:12px; display:flex; gap:8px;">
            <button type="submit">Actualizar</button>
            <a href="{{ route('admin.usuarios.index') }}">Cancelar</a>
        </div>
    </form>
@endsection
