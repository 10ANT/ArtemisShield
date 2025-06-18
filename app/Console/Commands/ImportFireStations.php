<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FireStation;
use League\Csv\Reader;
use SplFileObject;

class ImportFireStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:firestations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports fire station data from firestations.csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $csvFile = storage_path('app/Console/Commands/firestations.csv'); // Put CSV here, or adjust path
        if (!file_exists($csvFile)) {
            $this->error("CSV file not found at: {$csvFile}");
            return 1;
        }

        $this->info('Importing fire station data...');

        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $importedCount = 0;

        foreach ($records as $record) {
             try {
                FireStation::create([
                    'osm_id'               => $record['id'] ?? null,
                    'type'                 => $record['type'] ?? null,
                    'lat'                  => $record['lat'],
                    'lon'                  => $record['lon'],
                    'amenity'              => $record['amenity'] ?? null,
                    'name'                 => $record['name'] ?? null,
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
                    'building_levels'       => $record['building:levels'] ?? null,
                    'ref'                  => $record['ref'] ?? null,
                    'ref_nfirs'          => $record['ref:nfirs'] ?? null,
                    'fire_station_code'   => $record['fire_station:code'] ?? null,
                    'description'          => $record['description'] ?? null,
                    'wheelchair'           => $record['wheelchair'] ?? null,
                    'access'               => $record['access'] ?? null,
                    'note'                 => $record['note'] ?? null,
                    'wikidata'             => $record['wikidata'] ?? null,
                    'wikipedia'            => $record['wikipedia'] ?? null,
                    'fire_station_apparatus' => $record['fire_station:apparatus'] ?? null,
                    'fire_station_staffing'  => $record['fire_station:staffing'] ?? null,
                    'all_tags'             => isset($record['all_tags']) ? json_decode($record['all_tags'], true) : null,
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $this->error("Error importing record (ID: {$record['id']}): " . $e->getMessage());
            }
        }

        $this->info("Successfully imported {$importedCount} fire stations.");
        return 0;
    }
}