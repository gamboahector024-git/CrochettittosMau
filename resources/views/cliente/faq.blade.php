@extends('layouts.cliente')
@section('title', 'Preguntas Frecuentes')
@section('content')
<div class="faq-section">
    <h1 class="faq-title">Preguntas Frecuentes</h1>
    @if($categories->count())
        <div class="faq-categories">
            <button class="btn" onclick="filterFaqs('all')">Todas</button>
            @foreach($categories as $cat)
                <button class="btn" onclick="filterFaqs('{{ $cat }}')">{{ $cat }}</button>
            @endforeach
        </div>
    @endif
    <div class="faq-list">
        @forelse($faqs as $faq)
            <div class="faq-item" data-category="{{ $faq->category }}">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span>{{ $faq->question }}</span>
                    <span class="faq-arrow">▼</span>
                </button>
                <div class="faq-answer">
                    {!! nl2br(e($faq->answer)) !!}
                </div>
            </div>
        @empty
            <p>No hay preguntas frecuentes registradas.</p>
        @endforelse
    </div>
</div>
<script>
function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    item.classList.toggle('open');
}
function filterFaqs(cat) {
    document.querySelectorAll('.faq-categories .btn').forEach(btn => {
        btn.classList.toggle('active', btn.textContent.trim() === cat || (cat === 'all' && btn.textContent.trim() === 'Todas'));
    });
    document.querySelectorAll('.faq-item').forEach(item => {
        if (cat === 'all' || !cat) {
            item.style.display = '';
        } else {
            item.style.display = item.getAttribute('data-category') === cat ? '' : 'none';
        }
    });
}
// Activar el filtro "Todas" por defecto
document.addEventListener('DOMContentLoaded', function() {
    filterFaqs('all');
});
</script>
@endsection
