@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Editar Promoción</h1>

    <form action="{{ route('admin.promociones.update', $promocion) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.promociones._form')

        <div class="mt-3">
            <button type="submit">Actualizar</button>
            <a href="{{ route('admin.promociones.index') }}">Cancelar</a>
        </div>
    </form>
</div>
@endsection
