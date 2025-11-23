@extends('layouts.admin')

@section('title', 'FAQs - Preguntas Frecuentes')
@section('header', 'Gestión de FAQs')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Contenedor de acciones y búsqueda --}}
    <div class="card actions-header">
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">+ Nueva Pregunta</a>
        
        <form action="{{ route('admin.faqs.index') }}" method="GET" class="search-form">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar pregunta..." class="form-control">
            
            <select name="category" class="form-control">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="is_active" class="form-control">
                <option value="">Todos los estados</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Activas</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactivas</option>
            </select>
            
            <button type="submit" class="btn btn-primary">Buscar</button>
            
            @if(request()->has('q') || request()->has('category') || request()->has('is_active'))
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Limpiar</a>
            @endif
        </form>
    </div>

    {{-- Tabla de FAQs --}}
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Pregunta</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                    <tr>
                        <td>{{ $faq->sort_order }}</td>
                        <td>{{ Str::limit($faq->question, 60) }}</td>
                        <td>
                            @if($faq->category)
                                <span class="badge badge-secondary">{{ $faq->category }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($faq->is_active)
                                <span class="badge badge-success">Activa</span>
                            @else
                                <span class="badge badge-danger">Inactiva</span>
                            @endif
                        </td>
                        <td class="action-links">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-secondary">Editar</a>
                            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('¿Eliminar esta pregunta?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-row">No hay preguntas frecuentes registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-links">
        {{ $faqs->links() }}
    </div>
@endsection
