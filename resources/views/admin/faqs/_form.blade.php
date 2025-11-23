@php($isEdit = isset($faq))

<div class="form-group">
    <label for="question">Pregunta</label>
    <input id="question" name="question" type="text" class="form-control" value="{{ old('question', $isEdit ? $faq->question : '') }}" required maxlength="255">
    @error('question')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="answer">Respuesta</label>
    <textarea id="answer" name="answer" rows="5" class="form-control" required>{{ old('answer', $isEdit ? $faq->answer : '') }}</textarea>
    @error('answer')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label for="category">Categoría</label>
    <input id="category" name="category" type="text" class="form-control" value="{{ old('category', $isEdit ? $faq->category : '') }}" placeholder="Ej: Envíos, Pagos, Productos..." maxlength="100">
    <small class="form-text text-muted">Agrupar preguntas por tema ayuda a los clientes a encontrar respuestas más rápido.</small>
    @error('category')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="sort_order">Orden de visualización</label>
        <input id="sort_order" name="sort_order" type="number" class="form-control" value="{{ old('sort_order', $isEdit ? $faq->sort_order : 0) }}" min="0">
        <small class="form-text text-muted">Menor número aparece primero.</small>
        @error('sort_order')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group col-md-6">
        <label for="is_active">Estado</label>
        <select id="is_active" name="is_active" class="form-control">
            <option value="1" {{ old('is_active', $isEdit ? $faq->is_active : true) ? 'selected' : '' }}>Activa</option>
            <option value="0" {{ old('is_active', $isEdit ? $faq->is_active : true) ? '' : 'selected' }}>Inactiva</option>
        </select>
        @error('is_active')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>
