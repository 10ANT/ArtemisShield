<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\HistoricalFire;
use Exception;
use SplFileObject; // Use a memory-efficient file handler

class ImportFiresCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'import:fires {filepath} {--truncate}';

    /**
     * The console command description.
     */
    protected $description = 'Imports historical fire data from a given CSV file into the database with low memory usage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filepath = $this->argument('filepath');
        $truncate = $this->option('truncate');

        try {
            if (!file_exists($filepath)) {
                $this->error("File not found at path: {$filepath}");
                return Command::FAILURE;
            }

            if ($truncate) {
                if ($this->confirm('Are you sure you want to truncate the historical_fires table? This will delete all existing data.')) {
                    $this->info('Truncating historical_fires table...');
                    HistoricalFire::truncate();
                    $this->info('Table truncated successfully.');
                } else {
                    $this->info('Import cancelled by user.');
                    return Command::SUCCESS;
                }
            }

            $this->info('Starting the import process (low memory mode)...');
            $startTime = microtime(true);

            // Use SplFileObject for memory-efficient iteration
            $file = new SplFileObject($filepath, 'r');
            $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
            
            // Get the header row
            $file->rewind();
            $header = $file->current();
            
            $chunk = [];
            $chunkSize = 1000;
            $rowCount = 0;

            // Start progress bar without a known total
            $progressBar = $this->output->createProgressBar();
            $progressBar->start();

            // Move to the next line to skip the header in the loop
            $file->next();

            DB::transaction(function () use ($file, $header, &$chunk, $chunkSize, &$rowCount, $progressBar) {
                while (!$file->eof()) {
                    $row = $file->current();

                    // Handle potential malformed rows where row count doesn't match header count
                    if (count($row) === count($header)) {
                        $data = array_combine($header, $row);

                        // Skip if key columns are missing or empty
                        if (empty($data['latitude']) || empty($data['longitude'])) {
                            $file->next();
                            continue;
                        }

                        // Basic data cleaning/formatting
                        $data['acq_time'] = str_pad($data['acq_time'], 4, '0', STR_PAD_LEFT);

                        $chunk[] = $data;
                        $rowCount++;

                        if (count($chunk) >= $chunkSize) {
                            HistoricalFire::insert($chunk);
                            $progressBar->advance(count($chunk));
                            $chunk = []; // Reset the chunk
                        }
                    }
                    $file->next();
                }

                // Insert any remaining records in the last chunk
                if (!empty($chunk)) {
                    HistoricalFire::insert($chunk);
                    $progressBar->advance(count($chunk));
                }
            });

            $progressBar->finish();
            $file = null; // Close file handle

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            $this->newLine(2);
            $this->info("✅ Import successful!");
            $this->line("   - Records Imported: {$rowCount}");
            $this->line("   - Time Taken: {$duration} seconds");

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("An error occurred during import: " . $e->getMessage());
            // It's helpful to also log the line number
            $this->error("Error at line: " . $e->getLine());
            return Command::FAILURE;
        }
    }
}