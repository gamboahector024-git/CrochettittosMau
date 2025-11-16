@extends('layouts.cliente')

@section('title', 'Finalizar Compra - Crochettittos')

@section('content')
<div class="container">
    {{-- 1. Usamos el contenedor "glass" principal --}}
    <div class="profile-container">

        {{-- 2. Título estilizado --}}
        <h2 class="profile-title">Finalizar Compra</h2>

        {{-- 3. Usamos tu ruta de 'carrito.procesar' --}}
        <form action="{{ route('carrito.procesar') }}" method="POST" id="checkout-form">
            @csrf

            {{-- 4. Layout de 2 columnas (formulario / resumen) --}}
            <div class="checkout-layout">
                
                {{-- COLUMNA IZQUIERDA: Formulario --}}
                <div class="checkout-form-panel">
                    
                    <h3 class="form-subtitle">Dirección de Envío</h3>
                    
                    {{-- 5. Usamos el 'form-grid' de tu CSS --}}
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="calle">Calle y Número</label>
                            <input type="text" class="form-input" id="calle" name="calle" value="{{ old('calle') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="colonia">Colonia</label>
                            <input type="text" class="form-input" id="colonia" name="colonia" value="{{ old('colonia') }}" required>
                        </div>
                        
                        {{-- 6. Usamos tu campo 'municipio_ciudad' --}}
                        <div class="form-group">
                            <label for="municipio_ciudad">Municipio/Ciudad</label>
                            <input type="text" class="form-input" id="municipio_ciudad" name="municipio_ciudad" value="{{ old('municipio_ciudad') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="codigo_postal">Código Postal</label>
                            <input type="text" class="form-input" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <input type="text" class="form-input" id="estado" name="estado" value="{{ old('estado') }}" required>
                        </div>
                    </div>

                    <h3 class="form-subtitle">Método de Pago</h3>
                    <div class="form-group">
                        <label for="metodo_pago">Selecciona un método</label>
                        {{-- 7. Usamos tu <select> con las clases correctas --}}
                        <select class="form-input" id="metodo_pago" name="metodo_pago" required>
                            <option value="">Seleccione un método</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                        </select>
                    </div>

                </div>

                {{-- COLUMNA DERECHA: Resumen del Pedido --}}
                <div class="checkout-summary-panel">
                    <h3 class="form-subtitle">Resumen del Pedido</h3>
                    
                    {{-- 
                      ESTA ES LA PARTE QUE ARREGLA TU IMAGEN:
                      Usamos las clases .checkout-summary, .summary-item y .summary-total
                    --}}
                    <div class="checkout-summary">
                        
                        @foreach($items as $item)
                            <div class="summary-item">
                                <span>{{ $item->producto->nombre }} x{{ $item->cantidad }}</span>
                                <span>${{ number_format($item->producto->precio * $item->cantidad, 2) }}</span>
                            </div>
                        @endforeach
                        
                        <div class="summary-total">
                            <strong>Total:</strong>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                    </div>

                    {{-- 
                      ESTA ES LA OTRA PARTE QUE SE ARREGLA:
                      Usamos .secondary-button y .paypal-button
                    --}}
                    <div class="form-actions" style="margin-top: 1.5rem;">
                        <button type="submit" class="secondary-button">
                            Confirmar Pedido
                        </button>
                        <button id="btnPayPal" type="button" class="paypal-button">
                            Pagar con PayPal
                        </button>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- 10. Movimos tu script de PayPal aquí para mantener el HTML limpio --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnPayPal = document.getElementById('btnPayPal');
    if (btnPayPal) {
        btnPayPal.addEventListener('click', async () => {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';

            // Validamos que los campos existan antes de leer 'value'
            const calleEl = document.getElementById('calle');
            const coloniaEl = document.getElementById('colonia');
            const municipioEl = document.getElementById('municipio_ciudad');
            const cpEl = document.getElementById('codigo_postal');
            const estadoEl = document.getElementById('estado');

            const calle = calleEl ? calleEl.value.trim() : '';
            const colonia = coloniaEl ? coloniaEl.value.trim() : '';
            const municipio_ciudad = municipioEl ? municipioEl.value.trim() : '';
            const codigo_postal = cpEl ? cpEl.value.trim() : '';
            const estado = estadoEl ? estadoEl.value.trim() : '';

            if (!calle || !colonia || !municipio_ciudad || !codigo_postal || !estado) {
                alert('Por favor completa la dirección antes de pagar con PayPal.');
                return;
            }

            try {
                // Usamos la ruta de tu script
                const res = await fetch('/paypal/create-payment', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        calle,
                        colonia,
                        municipio_ciudad,
                        codigo_postal,
                        estado
                    })
                });

                const data = await res.json();
                if (!res.ok || data.error) {
                    const message = (data && data.message) ? data.message : 'Error al crear la orden de PayPal';
                    alert(message);
                    return;
                }

                const approve = (data.links || []).find(l => l.rel === 'approve');
                if (approve && approve.href) {
                    window.location.href = approve.href;
                } else {
                    alert('No se encontró el enlace de aprobación de PayPal.');
                }
            } catch (err) {
                console.error('Error de red PayPal:', err);
                alert('Error de red creando la orden de PayPal.');
            }
        });
    }
});
</script>
@endpush