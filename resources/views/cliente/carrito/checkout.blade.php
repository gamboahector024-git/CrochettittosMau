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

                    {{-- Pago con PayPal (mismo estilo que en peticiones personalizadas) --}}
                    <div class="form-actions" style="margin-top: 1.5rem;">
                        <div class="paypal-container">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency=MXN&disable-funding=card"></script>
<script src="{{ asset('js/cliente/checkout.js') }}"></script>
@endpush