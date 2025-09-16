<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncOrganizations extends Command
{
    protected $signature = 'sync:organizations';
    protected $description = 'Sync organizations from BKPSDM API';

    public function handle()
    {
        $apiUrl = 'https://siap-bkpsdm.probolinggokab.go.id/index.php/api/getPegawaiaktif';

        $this->info('Fetching data from API...');
        $response = Http::timeout(60)->get($apiUrl);

        if (!$response->successful()) {
            $this->error('Failed to fetch data: ' . $response->status());
            return 1;
        }

        $data = $response->json();

        $inserted = 0;
        $skipped = 0;

        DB::transaction(function () use ($data, &$inserted, &$skipped) {
            foreach ($data as $pegawai) {
                $orgName = $pegawai['SATKER_INDUK'] ?? null;
                $bkdOrgId = $pegawai['SATKER_INDUK_ID'] ?? null;

                if (!$bkdOrgId || !$orgName) {
                    continue;
                }

                // Check if already exists by bkd_organization_id
                $exists = DB::table('organization')
                    ->where('bkd_organization_id', $bkdOrgId)
                    ->exists();

                if ($exists) {
                    DB::table('organization')
                        ->where('bkd_organization_id', $bkdOrgId)
                        ->update([
                            'organization_name' => $orgName,
                            'updated_at' => now(),
                        ]);
                    $skipped++;
                } else {
                    DB::table('organization')->insert([
                        'organization_id' => (string) uniqid(),
                        'organization_name' => $orgName,
                        'bkd_organization_id' => $bkdOrgId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $inserted++;
                    $this->info("Inserted: {$orgName} (BKD ID: {$bkdOrgId})");
                }
            }
        });

        $this->info("\nSummary: {$inserted} inserted, {$skipped} updated/skipped.");
        return 0;
    }
}
