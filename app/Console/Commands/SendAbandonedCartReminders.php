<?php

namespace App\Console\Commands;

use App\Models\Carrito;
use App\Mail\AbandonedCartReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class SendAbandonedCartReminders extends Command
{
    protected $signature = 'cart:remind-abandoned';

    protected $description = 'Send reminder emails to users who left items in the cart after 2 hours of inactivity';

    public function handle(): int
    {
        $cutoff = now()->subHours(2);
        $processed = 0;

        // Cargar carritos con detalles y usuario
        $carritos = Carrito::with(['usuario', 'detalles'])
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

            // Debe haber pasado el umbral de 2 horas y no haber enviado aviso más reciente que la actividad
            if ($lastActivity->lte($cutoff) && (is_null($carrito->last_reminder_sent_at) || $carrito->last_reminder_sent_at->lt($lastActivity))) {
                try {
                    if (!$carrito->usuario || empty($carrito->usuario->email)) {
                        continue;
                    }

                    Mail::to($carrito->usuario->email)->queue(new AbandonedCartReminder($carrito));

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
