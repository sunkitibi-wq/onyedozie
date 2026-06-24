<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class ArtisanConsole extends Component
{
    public $commandString = '';
    public $outputBuffer = 'System online. Enter command or click quick actions to execute...';
    
    // Command whitelist for safety
    public $allowedCommands = [
        'migrate' => 'Run database migrations',
        'migrate:status' => 'Check database migration status',
        'db:seed' => 'Seed database tables',
        'cache:clear' => 'Clear application cache',
        'view:clear' => 'Clear compiled views',
        'route:clear' => 'Clear route cache',
        'config:clear' => 'Clear config cache',
        'optimize:clear' => 'Clear optimization cache',
    ];

    public function mount()
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function runCommand($cmd = null)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $commandToRun = trim($cmd ?: $commandString = $this->commandString);

        if (empty($commandToRun)) {
            return;
        }

        // Check safety whitelist (matching command name prefix)
        $baseCommand = explode(' ', $commandToRun)[0];
        if (!array_key_exists($baseCommand, $this->allowedCommands)) {
            $this->outputBuffer .= "\n\n$ > " . $commandToRun . "\nERROR: Command '{$baseCommand}' is restricted for safety reasons.";
            $this->commandString = '';
            return;
        }

        try {
            // Parse arguments if any
            $parts = explode(' ', $commandToRun);
            $cmdName = array_shift($parts);
            
            $parameters = [];
            foreach ($parts as $part) {
                if (str_contains($part, '=')) {
                    [$key, $value] = explode('=', $part, 2);
                    $parameters[$key] = $value;
                } else {
                    $parameters[$part] = true;
                }
            }

            $exitCode = Artisan::call($cmdName, $parameters);
            $output = Artisan::output();

            $this->outputBuffer .= "\n\n$ > " . $commandToRun . " (Exit Code: {$exitCode})\n" . ($output ?: 'Command executed successfully with no output.');

            \App\Models\ActivityLog::log(
                "Super Admin ran online artisan command: {$commandToRun}",
                null,
                auth()->user(),
                ['exit_code' => $exitCode],
                'Artisan Console'
            );

        } catch (\Exception $e) {
            $this->outputBuffer .= "\n\n$ > " . $commandToRun . "\nEXCEPTION ERROR: " . $e->getMessage();
        }

        $this->commandString = '';
    }

    public function clearConsole()
    {
        $this->outputBuffer = 'Console logs cleared. Enter command...';
    }

    public function render()
    {
        return view('livewire.artisan-console');
    }
}
