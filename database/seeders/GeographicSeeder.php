<?php

namespace Database\Seeders;

use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use Illuminate\Database\Seeder;
use RuntimeException;

class GeographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourcePath = base_path('lgas.txt');

        if (! file_exists($sourcePath)) {
            throw new RuntimeException('Geographic seeder source file not found: ' . $sourcePath);
        }

        $currentLga = null;
        $currentWard = null;

        foreach ($this->readSourceLines($sourcePath) as $line) {
            if ($line === '') {
                continue;
            }

            if ($this->isIgnoredLine($line)) {
                continue;
            }

            if ($this->isLgaHeading($line)) {
                $currentLga = Lga::updateOrCreate(
                    ['name' => $this->normalizeLgaName($line)],
                    ['state' => 'Anambra']
                );
                $currentWard = null;

                continue;
            }

            if ($this->isPollingUnitRow($line)) {
                if (! $currentLga || ! $currentWard) {
                    throw new RuntimeException('Polling unit row encountered before an LGA or ward heading: ' . $line);
                }

                [$code, $name] = $this->parsePollingUnitRow($line);

                PollingUnit::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $name,
                        'ward_id' => $currentWard->id,
                        'lat' => null,
                        'lng' => null,
                        'registered_voters' => 0,
                    ]
                );

                continue;
            }

            if (! $currentLga) {
                throw new RuntimeException('Ward heading encountered before an LGA heading: ' . $line);
            }

            $currentWard = Ward::updateOrCreate(
                [
                    'lga_id' => $currentLga->id,
                    'name' => $this->normalizeWardName($line),
                ],
                []
            );
        }
    }

    /**
     * @return array<int, string>
     */
    private function readSourceLines(string $path): array
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException('Unable to read geographic source file: ' . $path);
        }

        return preg_split('/\R/u', $contents) ?: [];
    }

    private function isIgnoredLine(string $line): bool
    {
        return str_starts_with($line, 'Search Result :')
            || str_starts_with($line, 'Polling Unit Delimeter');
    }

    private function isLgaHeading(string $line): bool
    {
        return (bool) preg_match('/\bLGA\b$/i', $line);
    }

    private function isPollingUnitRow(string $line): bool
    {
        return (bool) preg_match('/^\d{2}-\d{2}-\d{2}-\d{3}\s+.+\s+(EXISTING PU|NEW PU)$/i', $line);
    }

    private function parsePollingUnitRow(string $line): array
    {
        if (! preg_match('/^(\d{2}-\d{2}-\d{2}-\d{3})\s+(.+?)\s+(EXISTING PU|NEW PU)$/i', $line, $matches)) {
            throw new RuntimeException('Invalid polling unit row: ' . $line);
        }

        return [trim($matches[1]), $this->normalizeLabel($matches[2])];
    }

    private function normalizeLgaName(string $line): string
    {
        $name = preg_replace('/\bLGA\b$/i', '', $line) ?? $line;

        return $this->normalizeLabel($name, true);
    }

    private function normalizeWardName(string $line): string
    {
        $overrides = [
            'IDEMMILI NORTH' => 'Idemili North',
            'IDEMMILI SOUTH' => 'Idemili South',
            'NOBI III' => 'Nibo III',
        ];

        $normalized = $this->normalizeLabel($line, true);
        $lookupKey = strtoupper($normalized);

        return $overrides[$lookupKey] ?? $normalized;
    }

    private function normalizeLabel(string $value, bool $titleCase = false): string
    {
        $value = preg_replace('/\s+/', ' ', trim($value)) ?? trim($value);

        if ($titleCase) {
            $value = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
        }

        return $value;
    }
}
