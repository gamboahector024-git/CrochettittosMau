@extends('layouts.admin')

@section('title', 'Nuevo Usuario')
@section('header', 'Crear Usuario')

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

    <form action="{{ route('admin.usuarios.store') }}" method="POST">
        @csrf
        @include('admin.usuarios._form')
        <div style="margin-top:12px; display:flex; gap:8px;">
            <button type="submit">Guardar</button>
            <a href="{{ route('admin.usuarios.index') }}">Cancelar</a>
        </div>
    </form>
@endsection
