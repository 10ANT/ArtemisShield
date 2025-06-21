<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateEnvExample extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-env-example';

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
    $env = file(base_path('.env'));
    $example = collect($env)->map(function ($line) {
        return preg_match('/^\s*[^#=\s]+\s*=/',$line)
            ? preg_replace('/=.*/', '=YOUR_VALUE_HERE', $line)
            : $line;
    });
    file_put_contents(base_path('.env.example'), $example->implode(''));
    $this->info('.env.example generated!');
}

}
