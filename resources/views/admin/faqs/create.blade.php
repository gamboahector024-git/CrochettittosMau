@extends('layouts.admin')

@section('title', 'Nueva Pregunta Frecuente')
@section('header', 'Crear FAQ')

@section('content')
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

        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            
            @include('admin.faqs._form')
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Pregunta</button>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
