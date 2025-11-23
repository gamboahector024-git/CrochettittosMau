@extends('layouts.admin')

@section('title', 'Nueva Pregunta')

@section('content')
    <div class="content-header" style="margin-bottom: 30px;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">Nueva FAQ</h1>
        <p style="color: var(--text-muted);">Agrega información útil para tus clientes.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            @include('admin.faqs._form')
            
            <div class="form-actions" style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 20px; justify-content: flex-end;">
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary" style="margin-right: 10px;">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Pregunta</button>
            </div>
        </form>
    </div>
@endsection