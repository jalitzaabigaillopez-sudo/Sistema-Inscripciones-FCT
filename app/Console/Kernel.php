<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Definir los comandos programados.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:password-vencimiento')->daily();

        // Comando para cambiar el estado de los eventos a "finalizado" cuando se venza la fecha
        $schedule->command('eventos:finalizar')->daily();
    }

    /**
     * Registrar los comandos Artisan de la aplicación.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
