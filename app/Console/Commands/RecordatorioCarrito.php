<?php

namespace App\Console\Commands;

use App\Models\Carrito;
use App\Mail\RecordatorioCarrito as RecordatorioCarritoMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RecordatorioCarrito extends Command
{
    protected $signature = 'recordatorio:carrito';

    protected $description = 'Enviar recordatorios de carritos abandonados después de 1 minuto de inactividad';

    public function handle(): int
    {
        $cutoff = now()->subMinutes(1);
        $processed = 0;

        // Cargar carritos con usuario y productos de los detalles (incluye promoción activa si existe)
        $carritos = Carrito::with(['usuario', 'detalles.producto.promocionActiva'])
            ->whereHas('detalles')
            ->get();

        foreach ($carritos as $carrito) {
            // Calcular última actividad: mayor entre carrito.updated_at y updated_at de sus detalles
            $lastDetalleUpdate = $carrito->detalles->reduce(function ($carry, $detalle) {
                if ($detalle->updated_at && (!$carry || $detalle->updated_at->gt($carry))) {
                    return $detalle->updated_at;
                }
                return $carry;
            }, null);

            $lastActivity = $carrito->updated_at;
            if ($lastDetalleUpdate && $lastDetalleUpdate->gt($lastActivity)) {
                $lastActivity = $lastDetalleUpdate;
            }

            if (!$lastActivity) {
                continue;
            }

            // Debe haber pasado el umbral y no haber enviado aviso más reciente que la actividad
            if ($lastActivity->lte($cutoff) && (is_null($carrito->last_reminder_sent_at) || $carrito->last_reminder_sent_at->lt($lastActivity))) {
                try {
                    if (!$carrito->usuario || empty($carrito->usuario->email)) {
                        continue;
                    }

                    // Extraer productos desde los detalles del carrito (para la vista existente)
                    $productos = $carrito->detalles->map(function ($detalle) {
                        return $detalle->producto;
                    });

                    // Enviar correo (sin colas para pruebas inmediatas)
                    Mail::to($carrito->usuario->email)->send(new RecordatorioCarritoMail($carrito, $productos));

                    // Registrar momento del último recordatorio
                    $carrito->last_reminder_sent_at = now();
                    $carrito->save();

                    $processed++;
                } catch (\Throwable $e) {
                    Log::error('Failed to send abandoned cart reminder', [
                        'carrito_id' => $carrito->id_carrito ?? null,
                        'user_id' => $carrito->usuario->id_usuario ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Reminders sent: {$processed}");
        return self::SUCCESS;
    }
}
