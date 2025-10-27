@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Nueva Promoción</h1>

    <form action="{{ route('admin.promociones.store') }}" method="POST">
        @csrf
        @include('admin.promociones._form')

        <div class="mt-3">
            <button type="submit">Guardar</button>
            <a href="{{ route('admin.promociones.index') }}">Cancelar</a>
        </div>
    </form>
</div>
@endsection
