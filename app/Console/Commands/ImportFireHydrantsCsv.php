<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FireHydrant;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader; // You might need to install this package
use Exception;

class ImportFireHydrantsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:fire-hydrants-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports fire hydrant data from a CSV file into the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = 'data/fire_hydrants.csv'; // Path relative to storage/app/

        if (!Storage::exists($filePath)) {
            $this->error("CSV file not found: storage/app/{$filePath}");
            return Command::FAILURE;
        }

        try {
            // Read the CSV file
            $csv = Reader::createFromPath(Storage::path($filePath), 'r');
            $csv->setHeaderOffset(0); // Set the first row as headers

            $records = iterator_to_array($csv->getRecords());
            $progressBar = $this->output->createProgressBar(count($records));
            $progressBar->start();

            $importedCount = 0;
            $skippedCount = 0;

            foreach ($records as $record) {
                // Map CSV headers to database columns
                $data = [
                    'osm_id' => $record['id'],
                    'type' => $record['type'],
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

                try {
                    // Using updateOrCreate to handle potential duplicates if running multiple times
                    FireHydrant::updateOrCreate(
                        ['osm_id' => $data['osm_id']],
                        $data
                    );
                    $importedCount++;
                } catch (Exception $e) {
                    $this->warn("Skipping record ID {$data['osm_id']} due to error: " . $e->getMessage());
                    $skippedCount++;
                }
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();
            $this->info("CSV import complete. Imported: {$importedCount}, Skipped: {$skippedCount}.");
            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("An error occurred during CSV import: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}