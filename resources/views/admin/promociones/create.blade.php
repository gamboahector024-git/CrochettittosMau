@extends('layouts.admin')
@section('title', 'Nueva Promoción')
@section('content')
    <div class="content-header" style="margin-bottom: 30px;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">Crear Promoción</h1>
        <p style="color: var(--text-muted);">Configura una nueva oferta.</p>
    </div>
    @if ($errors->any()) <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div> @endif
    
    <div class="card">
        <form action="{{ route('admin.promociones.store') }}" method="POST">
            @csrf
            @include('admin.promociones._form', ['isEdit' => false, 'promocion' => null, 'producto' => $producto])
            <div class="form-actions" style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 20px; justify-content: flex-end;">
                <a href="{{ route('admin.promociones.index') }}" class="btn btn-secondary" style="margin-right: 10px;">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Promoción</button>
            </div>
        </form>
    </div>
@endsection