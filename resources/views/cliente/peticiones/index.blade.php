@extends('layouts.cliente')

@section('title', 'Mis Peticiones')

@section('content')
<div class="container" style="padding:20px;">
    <h1>Mis Peticiones</h1>

    @if($peticiones->isEmpty())
        <p>No tienes peticiones todavía. Puedes enviar una nueva desde el botón "Nueva Petición" en la barra superior.</p>
    @else
        <table class="table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($peticiones as $peticion)
                    <tr>
                        <td>#{{ $peticion->id_peticion }}</td>
                        <td>{{ $peticion->titulo }}</td>
                        <td><span class="status-badge status-{{ str_replace(' ', '-', $peticion->estado) }}">{{ ucfirst($peticion->estado) }}</span></td>
                        <td>{{ optional($peticion->created_at)->format('d/m/Y') }}</td>
                        <td><a href="{{ route('cliente.peticiones.show', $peticion->id_peticion) }}" class="nav-button nav-button-pastel-secondary">Ver</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:12px;">
            {{ $peticiones->links() }}
        </div>
    @endif
</div>
@endsection
