@extends('layouts.admin')

@section('title', 'Editar Pregunta Frecuente')
@section('header', 'Editar FAQ')

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

        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.faqs._form', ['faq' => $faq])
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Pregunta</button>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
