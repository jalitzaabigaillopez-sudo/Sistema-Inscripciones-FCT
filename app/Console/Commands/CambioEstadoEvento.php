<?php

namespace App\Console\Commands;

use App\Models\Evento;
use Illuminate\Console\Command;

class CambioEstadoEvento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventos:finalizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cambia el estado de los eventos finalizados a "finalizado".';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener todos los eventos que aún están "activos"
        // pero cuya fecha de finalización ya pasó.
        $events = Evento::where('estado', 'activo')
            ->where('fecha_final', '<', now())
            ->get();

        // Actualizar el estado de cada evento
        foreach ($events as $evento) {
            $evento->estado = 'finalizado';
            $evento->save();
        }

        $this->info(count($events) . ' eventos actualizados a "finalizado".');

        return 0;
    }
}
