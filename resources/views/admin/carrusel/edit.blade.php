@extends('layouts.admin')
@section('title', 'Editar Banner')

@section('content')
    <div class="content-header" style="margin-bottom: 30px;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">Editar Banner</h1>
    </div>

    @if ($errors->any()) <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div> @endif

    <div class="card">
        <form action="{{ route('admin.carrusel.update', $carrusel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.carrusel._form', ['carrusel' => $carrusel])
            
            <div class="form-actions" style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 20px; justify-content: flex-end;">
                <a href="{{ route('admin.carrusel.index') }}" class="btn btn-secondary" style="margin-right: 10px;">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Imagen</button>
            </div>
        </form>
    </div>
@endsection