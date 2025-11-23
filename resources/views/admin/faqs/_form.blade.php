@php
    $isEdit = isset($faq);
    $faq = $faq ?? null;
@endphp

<div class="form-grid">
    
    {{-- COLUMNA IZQUIERDA: Contenido --}}
    <div class="left-column">
        <h3 style="color: var(--accent); font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid var(--color-petal-glaze); display:inline-block;">
            Contenido
        </h3>

        <div class="form-group">
            <label for="question">Pregunta</label>
            <input id="question" name="question" type="text" class="form-control" 
                   value="{{ old('question', $isEdit ? $faq->question : '') }}" 
                   placeholder="Ej. ¿Cuánto tardan los envíos?" required maxlength="255">
            @error('question')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="answer">Respuesta</label>
            <textarea id="answer" name="answer" rows="6" class="form-control" 
                      placeholder="Explica detalladamente la respuesta..." required>{{ old('answer', $isEdit ? $faq->answer : '') }}</textarea>
            @error('answer')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- COLUMNA DERECHA: Configuración --}}
    <div class="right-column">
        <h3 style="color: var(--accent); font-size: 1.2rem; margin-bottom: 20px; border-bottom: 2px solid var(--color-petal-glaze); display:inline-block;">
            Configuración
        </h3>

        <div class="form-group">
            <label for="category">Categoría</label>
            <input id="category" name="category" type="text" class="form-control" 
                   value="{{ old('category', $isEdit ? $faq->category : '') }}" 
                   placeholder="Ej. Pagos" maxlength="100">
            <small style="color: var(--text-muted);">Agrupa preguntas similares (Envíos, Pagos, etc).</small>
            @error('category')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="row-2-cols">
            <div class="form-group">
                <label for="sort_order">Orden</label>
                <input id="sort_order" name="sort_order" type="number" class="form-control" 
                       value="{{ old('sort_order', $isEdit ? $faq->sort_order : 0) }}" min="0">
                @error('sort_order')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="is_active">Estado</label>
                <select id="is_active" name="is_active" class="form-control">
                    <option value="1" {{ old('is_active', $isEdit ? $faq->is_active : true) ? 'selected' : '' }}>Visible</option>
                    <option value="0" {{ old('is_active', $isEdit ? $faq->is_active : true) ? '' : 'selected' }}>Oculta</option>
                </select>
                @error('is_active')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        
        <div style="background: rgba(255,255,255,0.4); padding: 15px; border-radius: 12px; border: 1px solid var(--border); margin-top: 10px;">
            <small class="text-muted">
                💡 <strong>Tip:</strong> Usa números bajos (0, 1, 2) para las preguntas más importantes que quieras que aparezcan primero.
            </small>
        </div>
    </div>
</div>