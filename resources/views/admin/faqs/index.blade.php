@extends('layouts.admin')

@section('title', 'Preguntas Frecuentes')

@section('content')
    <div class="content-header" style="margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); color: var(--accent); font-size: 2.5rem;">
            Gestión de FAQs
        </h1>
        <p style="color: var(--text-muted);">Ayuda a tus clientes a resolver sus dudas rápidamente.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filtros (Estilo Glass) --}}
    <div class="card search-card" style="margin-bottom: 2rem;">
        <div class="actions-header" style="margin-bottom: 0;">
            <form action="{{ route('admin.faqs.index') }}" method="GET" class="search-form" style="flex-grow: 1;">
                
                <div class="form-group" style="flex-grow: 1;">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="🔍 Buscar pregunta..." 
                           class="form-control" style="border-radius: 20px;">
                </div>
                
                <div class="form-group">
                    <select name="category" class="form-control" style="border-radius: 20px; min-width: 180px;">
                        <option value="">📂 Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select name="is_active" class="form-control" style="border-radius: 20px; min-width: 150px;">
                        <option value="">Estado</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Activas</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactivas</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="border-radius: 20px;">Buscar</button>
                
                @if(request()->anyFilled(['q', 'category', 'is_active']))
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary" style="border-radius: 20px;">Limpiar</a>
                @endif
            </form>

            <div class="action-links" style="margin-left: 15px;">
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary shadow-hover">
                    <span style="font-size: 1.2em; vertical-align: middle;">+</span> Nueva
                </a>
            </div>
        </div>
    </div>

    {{-- Tabla de FAQs --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="table-hover">
            <thead style="background-color: rgba(255,255,255,0.4);">
                <tr>
                    <th scope="col" style="padding-left: 20px; width: 80px;">Orden</th>
                    <th scope="col">Pregunta y Respuesta</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Estado</th>
                    <th scope="col" style="text-align: right; padding-right: 30px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                    <tr style="transition: background 0.3s;">
                        <td style="padding-left: 20px; font-weight: 700; color: var(--text-muted);">
                            #{{ $faq->sort_order }}
                        </td>
                        
                        {{-- Pregunta Principal --}}
                        <td style="max-width: 400px;">
                            <div style="font-weight: 600; color: var(--text-dark); margin-bottom: 4px;">
                                {{ $faq->question }}
                            </div>
                            <div style="font-size: 0.85em; color: var(--text-muted); line-height: 1.4;">
                                {{ Str::limit($faq->answer, 80) }}
                            </div>
                        </td>

                        {{-- Categoría (Badge Gris/Azul) --}}
                        <td>
                            @if($faq->category)
                                <span class="status-badge badge-gray" style="border: 1px solid var(--border);">
                                    {{ $faq->category }}
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">—</span>
                            @endif
                        </td>

                        {{-- Estado --}}
                        <td>
                            @if($faq->is_active)
                                <span class="status-badge status-activa">Visible</span>
                            @else
                                <span class="status-badge status-inactiva">Oculta</span>
                            @endif
                        </td>

                        {{-- Acciones con Iconos --}}
                        <td style="text-align: right; padding-right: 30px;">
                            <div class="action-links" style="justify-content: flex-end;">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn-icon" title="Editar">
                                    ✏️
                                </a>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('¿Eliminar esta pregunta frecuente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" title="Eliminar">
                                        🗑️
                                    </button>
                                </form>
                            </div>
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

    <div class="pagination-links" style="margin-top: 20px;">
        {{ $faqs->links() }}
    </div>
@endsection