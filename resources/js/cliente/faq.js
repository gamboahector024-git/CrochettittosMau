// Funcionalidad de preguntas frecuentes (FAQ)
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar FAQs
    initializeFaqItems();
    initializeFaqSearch();
});

// Función para alternar FAQ (llamada desde onclick en la vista)
window.toggleFaq = function(btn) {
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
};

// Función para filtrar FAQs por categoría
window.filterFaqs = function(cat, btnElement) {
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
};

// Inicializar elementos FAQ (versión legacy para compatibilidad)
function initializeFaqItems() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const icon = question?.querySelector('.faq-icon');
        
        if (question && answer && !question.hasAttribute('onclick')) {
            question.addEventListener('click', function() {
                const isOpen = answer.style.display === 'block';
                
                // Cerrar todas las demás preguntas
                faqItems.forEach(otherItem => {
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    const otherIcon = otherItem.querySelector('.faq-icon');
                    
                    if (otherAnswer && otherAnswer !== answer) {
                        otherAnswer.style.display = 'none';
                        otherItem.classList.remove('active');
                        if (otherIcon) {
                            otherIcon.textContent = '+';
                        }
                    }
                });
                
                // Alternar la pregunta actual
                if (isOpen) {
                    answer.style.display = 'none';
                    item.classList.remove('active');
                    if (icon) icon.textContent = '+';
                } else {
                    answer.style.display = 'block';
                    item.classList.add('active');
                    if (icon) icon.textContent = '−';
                }
            });
        }
    });
}

// Inicializar búsqueda de FAQs
function initializeFaqSearch() {
    const searchInput = document.getElementById('faq-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                const answer = item.querySelector('.faq-answer');
                
                if (question && answer) {
                    const questionText = question.textContent.toLowerCase();
                    const answerText = answer.textContent.toLowerCase();
                    
                    if (questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        });
    }
}
