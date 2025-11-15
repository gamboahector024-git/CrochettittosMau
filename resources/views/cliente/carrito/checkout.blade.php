@extends('layouts.cliente')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Finalizar Compra</h1>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Dirección de Envío</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('carrito.procesar') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="calle" class="form-label">Calle y Número</label>
                                <input type="text" class="form-control" id="calle" name="calle" value="{{ old('calle') }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="colonia" class="form-label">Colonia</label>
                                <input type="text" class="form-control" id="colonia" name="colonia" value="{{ old('colonia') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="municipio_ciudad" class="form-label">Municipio/Ciudad</label>
                                <input type="text" class="form-control" id="municipio_ciudad" name="municipio_ciudad" value="{{ old('municipio_ciudad') }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago</label>
                            <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                <option value="">Seleccione un método</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                <option value="transferencia">Transferencia Bancaria</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary">Confirmar Pedido</button>
                        
                        <button id="btnPayPal" type="button">Pagar con PayPal</button>
                        <script>
                        document.getElementById('btnPayPal').addEventListener('click', async () => {
                            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                            const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';

                            const calle = document.getElementById('calle')?.value?.trim();
                            const colonia = document.getElementById('colonia')?.value?.trim();
                            const municipio_ciudad = document.getElementById('municipio_ciudad')?.value?.trim();
                            const codigo_postal = document.getElementById('codigo_postal')?.value?.trim();
                            const estado = document.getElementById('estado')?.value?.trim();

                            if (!calle || !colonia || !municipio_ciudad || !codigo_postal || !estado) {
                                alert('Por favor completa la dirección antes de pagar con PayPal.');
                                return;
                            }

                            try {
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
                                alert('Error de red creando la orden de PayPal.');
                            }
                        });
                        </script>

                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    @foreach($items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item->producto->nombre }} x{{ $item->cantidad }}</span>
                            <span>${{ number_format($item->producto->precio * $item->cantidad, 2) }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection