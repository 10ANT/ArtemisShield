<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FireHydrant;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Exception;
use Illuminate\Support\Facades\DB;

class ImportFireHydrantsCsv extends Command
{
    /**
     * CONFIGURATION: Set your CSV directory path here
     * This path is relative to your Laravel project root
     * Examples:
     * - 'storage/app/csv_files/' 
     * - 'public/imports/'
     * - 'C:/path/to/your/csv/files/' (absolute path)
     */
    private const CSV_DIRECTORY = 'app/Console/Commands/fire_hydrants/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:fire-hydrants-csv 
                            {--chunk-size=1000 : Number of records to process at once} 
                            {--file= : Specific file to import}
                            {--list : List all found CSV files}
                            {--dir= : Override directory path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports fire hydrant data from all CSV files in the specified directory.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Handle list option
        if ($this->option('list')) {
            return $this->listFoundFiles();
        }
        
        $chunkSize = (int) $this->option('chunk-size');
        $specificFile = $this->option('file');
        
        if ($specificFile) {
            $files = [$specificFile];
        } else {
            // Get all CSV files from the specified directory
            $files = $this->getAllCsvFiles();
        }

        if (empty($files)) {
            $this->error('No CSV files found to import.');
            $this->line('');
            $this->info('Directory being searched: ' . $this->getCsvDirectory());
            $this->info('Try the following:');
            $this->info('1. Run: php artisan import:fire-hydrants-csv --list');
            $this->info('2. Check if CSV files exist in the directory');
            $this->info('3. Use --file option to specify exact file path');
            $this->info('4. Use --dir option to override directory path');
            return Command::FAILURE;
        }

        $this->info("Found " . count($files) . " CSV file(s) to import.");
        $this->info("Directory: " . $this->getCsvDirectory());
        $this->info("Using chunk size: {$chunkSize}");
        $this->line('');

        // Show files that will be processed
        $this->info("Files to be processed:");
        foreach ($files as $file) {
            $size = file_exists($file) ? round(filesize($file) / 1024 / 1024, 2) : 0;
            $this->info("- " . basename($file) . " ({$size} MB)");
        }
        $this->line('');

        // Ask for confirmation
        // if (!$this->confirm('Do you want to proceed with the import?')) {
        //     $this->info('Import cancelled.');
        //     return Command::SUCCESS;
        // }

        $totalImported = 0;
        $totalSkipped = 0;
        $totalProcessed = 0;

        foreach ($files as $filePath) {
            $this->info("Processing file: " . basename($filePath));
            
            $result = $this->processFile($filePath, $chunkSize);
            
            if ($result === false) {
                $this->error("Failed to process file: " . basename($filePath));
                continue;
            }

            $totalImported += $result['imported'];
            $totalSkipped += $result['skipped'];
            $totalProcessed += $result['processed'];

            $this->info("File completed. Imported: {$result['imported']}, Skipped: {$result['skipped']}");
            $this->line('');
            
            // Force garbage collection between files
            gc_collect_cycles();
        }

        $this->info("All files processed!");
        $this->info("Total records processed: {$totalProcessed}");
        $this->info("Total imported: {$totalImported}");
        $this->info("Total skipped: {$totalSkipped}");

        return Command::SUCCESS;
    }

    /**
     * Get the CSV directory path
     */
    private function getCsvDirectory(): string
    {
        $directory = $this->option('dir') ?? self::CSV_DIRECTORY;
        
        // If it's not an absolute path, make it relative to Laravel root
        if (!$this->isAbsolutePath($directory)) {
            $directory = base_path($directory);
        }
        
        // Ensure directory ends with slash
        return rtrim($directory, '/') . '/';
    }

    /**
     * Check if path is absolute
     */
    private function isAbsolutePath(string $path): bool
    {
        return isset($path[0]) && ($path[0] === '/' || (strlen($path) > 3 && ctype_alpha($path[0]) && $path[1] === ':'));
    }

    /**
     * Get all CSV files from the specified directory
     */
    private function getAllCsvFiles(): array
    {
        $directory = $this->getCsvDirectory();
        
        $this->info("Searching for CSV files in: {$directory}");
        
        if (!is_dir($directory)) {
            $this->error("Directory does not exist: {$directory}");
            return [];
        }

        $files = [];
        $csvFiles = glob($directory . '*.csv');
        
        if ($csvFiles === false) {
            $this->error("Error reading directory: {$directory}");
            return [];
        }

        // Sort files to process them in order
        sort($csvFiles);
        
        foreach ($csvFiles as $file) {
            if (is_file($file) && is_readable($file)) {
                $files[] = $file;
            }
        }

        $this->info("Found " . count($files) . " CSV file(s)");
        
        return $files;
    }

    /**
     * List all found CSV files
     */
    private function listFoundFiles()
    {
        $files = $this->getAllCsvFiles();
        
        if (empty($files)) {
            $this->warn("No CSV files found.");
            $this->info("Directory searched: " . $this->getCsvDirectory());
        } else {
            $this->info("Found CSV files in: " . $this->getCsvDirectory());
            $this->line('');
            $totalSize = 0;
            
            foreach ($files as $file) {
                $size = filesize($file);
                $sizeMB = round($size / 1024 / 1024, 2);
                $totalSize += $size;
                $this->info("- " . basename($file) . " ({$sizeMB} MB)");
            }
            
            $totalSizeMB = round($totalSize / 1024 / 1024, 2);
            $this->line('');
            $this->info("Total: " . count($files) . " file(s), {$totalSizeMB} MB");
        }
        
        return Command::SUCCESS;
    }

    /**
     * Get all fire hydrant CSV files
     */
    private function getFireHydrantCsvFiles(): array
    {
        // This method is now replaced by getAllCsvFiles()
        // Keeping for backward compatibility
        return $this->getAllCsvFiles();
    }

    /**
     * Process a single CSV file
     */
    private function processFile(string $filePath, int $chunkSize): array|false
    {
        try {
            if (!file_exists($filePath)) {
                $this->error("File not found: {$filePath}");
                return false;
            }

            // Read the CSV file
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);

            // Get total count for progress bar (this is memory efficient)
            $totalRecords = iterator_count($csv->getRecords());
            $this->info("Total records in file: {$totalRecords}");

            // Reset the reader
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);

            $progressBar = $this->output->createProgressBar($totalRecords);
            $progressBar->start();

            $importedCount = 0;
            $skippedCount = 0;
            $processedCount = 0;
            $chunk = [];

            foreach ($csv->getRecords() as $record) {
                $chunk[] = $record;
                $processedCount++;

                // Process chunk when it reaches the specified size
                if (count($chunk) >= $chunkSize) {
                    $result = $this->processChunk($chunk);
                    $importedCount += $result['imported'];
                    $skippedCount += $result['skipped'];
                    
                    $progressBar->advance(count($chunk));
                    $chunk = []; // Clear the chunk
                    
                    // Force garbage collection
                    gc_collect_cycles();
                }
            }

            // Process remaining records in the last chunk
            if (!empty($chunk)) {
                $result = $this->processChunk($chunk);
                $importedCount += $result['imported'];
                $skippedCount += $result['skipped'];
                $progressBar->advance(count($chunk));
            }

            $progressBar->finish();
            $this->newLine();

            return [
                'imported' => $importedCount,
                'skipped' => $skippedCount,
                'processed' => $processedCount
            ];

        } catch (Exception $e) {
            $this->error("Error processing file " . basename($filePath) . ": " . $e->getMessage());
            return false;
        }
    }

    /**
     * Process a chunk of records
     */
    private function processChunk(array $chunk): array
    {
        $imported = 0;
        $skipped = 0;
        
        // Use database transaction for better performance
        DB::beginTransaction();

        try {
            foreach ($chunk as $record) {
                $data = $this->mapRecordToData($record);
                
                if ($data === null) {
                    $skipped++;
                    continue;
                }

                try {
                    FireHydrant::updateOrCreate(
                        ['osm_id' => $data['osm_id']],
                        $data
                    );
                    $imported++;
                } catch (Exception $e) {
                    $this->warn("Skipping record ID {$data['osm_id']} due to error: " . $e->getMessage());
                    $skipped++;
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $this->error("Chunk processing failed: " . $e->getMessage());
            $skipped += count($chunk);
        }

        return ['imported' => $imported, 'skipped' => $skipped];
    }

    /**
     * Map CSV record to database data array
     */
    private function mapRecordToData(array $record): ?array
    {
        // Validate required fields
        if (empty($record['id']) || empty($record['lat']) || empty($record['lon'])) {
            return null;
        }

        try {
            return [
                'osm_id' => $record['id'],
                'type' => $record['type'] ?? 'node',
                'lat' => (float) $record['lat'],
                'lon' => (float) $record['lon'],
                'emergency' => $record['emergency'] ?? null,
                'fire_hydrant_type' => $record['fire_hydrant:type'] ?? null,
                'fire_hydrant_diameter' => $record['fire_hydrant:diameter'] ?? null,
                'operator' => $record['operator'] ?? null,
                'colour' => $record['colour'] ?? null,
                'color' => $record['color'] ?? null,
                'ref' => $record['ref'] ?? null,
                'description' => $record['description'] ?? null,
                'addr_street' => $record['addr:street'] ?? null,
                'addr_housenumber' => $record['addr:housenumber'] ?? null,
                'addr_city' => $record['addr:city'] ?? null,
                'addr_postcode' => $record['addr:postcode'] ?? null,
                'addr_state' => $record['addr:state'] ?? null,
                'source' => $record['source'] ?? null,
                'survey_date' => $record['survey:date'] ?? null,
                'fire_hydrant_position' => $record['fire_hydrant:position'] ?? null,
                'fire_hydrant_pressure' => $record['fire_hydrant:pressure'] ?? null,
                'access' => $record['access'] ?? null,
                'note' => $record['note'] ?? null,
                'water_source' => $record['water_source'] ?? null,
                'osm_timestamp' => !empty($record['timestamp']) ? \Carbon\Carbon::parse($record['timestamp']) : null,
                'osm_version' => (int) ($record['version'] ?? 0),
                'osm_changeset' => (int) ($record['changeset'] ?? 0),
                'osm_user' => $record['user'] ?? null,
                'osm_uid' => (int) ($record['uid'] ?? 0),
                'all_tags' => !empty($record['all_tags']) ? json_decode($record['all_tags'], true) : null,
            ];
        } catch (Exception $e) {
            $this->warn("Error mapping record: " . $e->getMessage());
            return null;
        }
    }
}