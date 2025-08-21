<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;

class PasswordVencimiento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:password-vencimiento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Usuario::query()->where('password_vencimiento', '>', 0)->decrement('password_vencimiento ', 1);
        $this->info('Columna password_vencimiento actualizada correctamente.');
    }
}
