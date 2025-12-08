@extends('layouts.cliente')

@section('title', 'Preguntas Frecuentes')

@section('content')

{{-- Vinculamos el CSS externo --}}
@vite('resources/css/faq.css')

<div class="faq-section">
    <div class="faq-container-width">
        
        <h1 class="faq-title">Preguntas Frecuentes</h1>

        @if($categories->count())
            <div class="faq-categories">
                {{-- Botón 'Todas' activo por defecto --}}
                <button class="btn active" onclick="filterFaqs('all', this)">Todas</button>
                @foreach($categories as $cat)
                    <button class="btn" onclick="filterFaqs('{{ $cat }}', this)">{{ $cat }}</button>
                @endforeach
            </div>
        @endif

        <div class="faq-list">
            @forelse($faqs as $faq)
                <div class="faq-item" data-category="{{ $faq->category }}">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span>{{ $faq->question }}</span>
                        {{-- Usamos un SVG o icono en lugar de texto simple para la flecha --}}
                        <span class="faq-arrow">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </button>
                    
                    <div class="faq-answer">
                        {{-- Div interno para padding suave --}}
                        <div class="faq-answer-content">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #7A6A74; padding: 40px;">No hay preguntas frecuentes registradas.</p>
            @endforelse
        </div>

    </div>
</div>

@endsection

@push('scripts')
@vite('resources/js/cliente/faq.js')
@endpush