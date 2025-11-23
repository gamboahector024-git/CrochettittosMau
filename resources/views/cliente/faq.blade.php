@extends('layouts.cliente')

@section('title', 'Preguntas Frecuentes')

@section('content')

{{-- Vinculamos el CSS externo --}}
<link rel="stylesheet" href="{{ asset('css/faq.css') }}">

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
<script>
    function toggleFaq(btn) {
        const item = btn.closest('.faq-item');
        const answer = item.querySelector('.faq-answer');
        
        // Alternar clase visual
        item.classList.toggle('open');

        // Calcular altura para animación suave (slide down)
        if (item.classList.contains('open')) {
            answer.style.maxHeight = answer.scrollHeight + "px";
        } else {
            answer.style.maxHeight = null;
        }
    }

    function filterFaqs(cat, btnElement) {
        // 1. Actualizar botones visualmente
        if(btnElement) {
            document.querySelectorAll('.faq-categories .btn').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');
        }

        // 2. Filtrar items
        document.querySelectorAll('.faq-item').forEach(item => {
            if (cat === 'all' || item.getAttribute('data-category') === cat) {
                // Mostrar con animación de opacidad
                item.classList.remove('hidden');
                item.style.opacity = '0';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 50);
            } else {
                // Ocultar
                item.classList.add('hidden');
            }
        });
    }
</script>
@endpush