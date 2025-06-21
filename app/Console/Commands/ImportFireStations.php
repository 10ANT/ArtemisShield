<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FireStation;
use League\Csv\Reader;
use SplFileObject;
use Illuminate\Support\Facades\File;

class ImportFireStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:firestations {--folder=app/Console/Commands/firestations : Path to folder containing CSV files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports fire station data from all CSV files in the specified folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folderPath = $this->option('folder');
        
        if (!File::exists($folderPath)) {
            $this->error("Folder not found at: {$folderPath}");
            return 1;
        }

        // Get all CSV files from the folder
        $csvFiles = File::glob($folderPath . '/*.csv');
        
        if (empty($csvFiles)) {
            $this->error("No CSV files found in folder: {$folderPath}");
            return 1;
        }

        $this->info("Found " . count($csvFiles) . " CSV files to import:");
        foreach ($csvFiles as $file) {
            $this->line("  - " . basename($file));
        }

        if (!$this->confirm('Do you want to proceed with the import?')) {
            $this->info('Import cancelled.');
            return 0;
        }

        $totalImported = 0;
        $totalErrors = 0;
        $processedFiles = 0;

        // Process each CSV file
        foreach ($csvFiles as $csvFile) {
            $fileName = basename($csvFile);
            $this->info("\nProcessing file: {$fileName}");
            
            try {
                $result = $this->processCSVFile($csvFile);
                $totalImported += $result['imported'];
                $totalErrors += $result['errors'];
                $processedFiles++;
                
                $this->info("  ✓ Imported {$result['imported']} records from {$fileName}");
                if ($result['errors'] > 0) {
                    $this->warn("  ⚠ {$result['errors']} errors encountered in {$fileName}");
                }
                
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to process {$fileName}: " . $e->getMessage());
                $totalErrors++;
            }
        }

        // Final summary
        $this->newLine();
        $this->info("=== IMPORT SUMMARY ===");
        $this->info("Files processed: {$processedFiles}/" . count($csvFiles));
        $this->info("Total records imported: {$totalImported}");
        if ($totalErrors > 0) {
            $this->warn("Total errors: {$totalErrors}");
        } else {
            $this->info("No errors encountered!");
        }

        return $totalErrors > 0 ? 1 : 0;
    }

    /**
     * Process a single CSV file
     *
     * @param string $csvFile
     * @return array
     */
    private function processCSVFile($csvFile)
    {
        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $importedCount = 0;
        $errorCount = 0;

        // Create progress bar for this file
        $totalRecords = iterator_count($csv->getRecords());
        $csv->setHeaderOffset(0); // Reset after counting
        $records = $csv->getRecords(); // Get fresh iterator
        
        $progressBar = $this->output->createProgressBar($totalRecords);
        $progressBar->setFormat('  %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');

        foreach ($records as $record) {
            try {
                // Check if this record already exists (by OSM ID)
                $existingStation = FireStation::where('osm_id', $record['id'] ?? null)->first();
                
                if ($existingStation) {
                    // Skip or update existing record
                    $progressBar->advance();
                    continue;
                }

                FireStation::create([
                    'osm_id'               => $record['id'] ?? null,
                    'type'                 => $record['type'] ?? null,
                    'lat'                  => $record['lat'],
                    'lon'                  => $record['lon'],
                    'amenity'              => $record['amenity'] ?? null,
                    'name'                 => $record['name'] ?? null,
                    'name_en'              => $record['name:en'] ?? null,
                    'official_name'        => $record['official_name'] ?? null,
                    'alt_name'             => $record['alt_name'] ?? null,
                    'operator'             => $record['operator'] ?? null,
                    'operator_type'        => $record['operator:type'] ?? null,
                    'fire_station_type'    => $record['fire_station:type'] ?? null,
                    'addr_street'          => $record['addr:street'] ?? null,
                    'addr_housenumber'     => $record['addr:housenumber'] ?? null,
                    'addr_city'            => $record['addr:city'] ?? null,
                    'addr_postcode'        => $record['addr:postcode'] ?? null,
                    'addr_state'           => $record['addr:state'] ?? null,
                    'addr_country'         => $record['addr:country'] ?? null,
                    'phone'                => $record['phone'] ?? null,
                    'emergency'            => $record['emergency'] ?? null,
                    'website'              => $record['website'] ?? null,
                    'email'                => $record['email'] ?? null,
                    'opening_hours'        => $record['opening_hours'] ?? null,
                    'contact_phone'        => $record['contact:phone'] ?? null,
                    'contact_website'      => $record['contact:website'] ?? null,
                    'contact_email'        => $record['contact:email'] ?? null,
                    'source'               => $record['source'] ?? null,
                    'building'             => $record['building'] ?? null,
                    'building_levels'      => $record['building:levels'] ?? null,
                    'ref'                  => $record['ref'] ?? null,
                    'ref_nfirs'            => $record['ref:nfirs'] ?? null,
                    'fire_station_code'    => $record['fire_station:code'] ?? null,
                    'description'          => $record['description'] ?? null,
                    'wheelchair'           => $record['wheelchair'] ?? null,
                    'access'               => $record['access'] ?? null,
                    'note'                 => $record['note'] ?? null,
                    'wikidata'             => $record['wikidata'] ?? null,
                    'wikipedia'            => $record['wikipedia'] ?? null,
                    'fire_station_apparatus' => $record['fire_station:apparatus'] ?? null,
                    'fire_station_staffing'  => $record['fire_station:staffing'] ?? null,
                    'timestamp'            => $record['timestamp'] ?? null,
                    'version'              => $record['version'] ?? null,
                    'changeset'            => $record['changeset'] ?? null,
                    'user'                 => $record['user'] ?? null,
                    'uid'                  => $record['uid'] ?? null,
                    'all_tags'             => isset($record['all_tags']) ? json_decode($record['all_tags'], true) : null,
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $errorCount++;
                // Log detailed error for debugging
                $this->line("\n    Error importing record (ID: {$record['id']}): " . $e->getMessage());
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        return [
            'imported' => $importedCount,
            'errors' => $errorCount
        ];
    }
}