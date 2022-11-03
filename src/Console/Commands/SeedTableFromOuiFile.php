<?php

namespace Sealution\MacResolver\Console\Commands;

use Sealution\MacResolver\Exceptions\OuiFileNotFoundException;
use Sealution\MacResolver\OuiFile;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SeedTableFromOuiFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mac:insert {--file= : The file to seed database.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial seed command from each assignment file published by IEEE.';

    protected OuiFile $file;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws OuiFileNotFoundException
     *
     */
    public function handle()
    {

        $choice = $this->option('file');

        if ($choice === 'All') {
            $names = ($this->getFilesNames());
            foreach($names as $name){
                $this->file = new OuiFile(
                    $name,
                    true
                );
                self::seedTable(self::processCsv());
            }

        } else {

            $this->file = new OuiFile(
                $this->option('file') ?? '',
                $this->option('file') != null
            );

            self::seedTable(self::processCsv());
        }

        return 0;
    }

    /**
     * Process the recently downloaded oui.csv.
     *
     * @return array
     */
    private function processCsv(): array
    {
        $this->info(' Processing the CSV file');

        $csv = array_map('str_getcsv', file(storage_path($this->file->fullPath())));

        array_shift($csv);

        $column_names = ['registry', 'oui', 'organization', 'address'];

        $data = [];
        foreach ($csv as $row) {
            $dataPoint = [];
            foreach ($row as $key => $value) {

                $resolvedKey = match ($key) {
                    0 => "registry",
                    1 => 'oui',
                    2 => 'organization',
                    3 => 'address',
                    default => 'unknown'
                };

                $dataPoint[$resolvedKey] = $value;

            }
            $dataPoint['created_at'] = Carbon::now()->toDateTimeString();
            $data[] = $dataPoint;
        }

        return $data;
    }

    private function getFilesNames()
    {
        $files = DB::table('ieee_oui_files')->where('is_deleted', false)->get();

        $filesNames = $files->map(fn($file) => $file->name)->toArray();
        return $filesNames;
    }

    /**
     * Seed the table ieee_oui_vendors.
     *
     * @param array $csv
     */
    private function seedTable(array $csv): void
    {
        $this->info(' Seeding the table ieee_oui_assignments');

        // $chunks = (collect($csv))->chunk(100);

        $this->output->progressStart(count($csv));
        $errors = [];

        foreach ($csv as $assignment) {
            try {
                $oui = $assignment['oui'];

                DB::table('ieee_oui_assignments')->updateOrInsert(['oui' => $oui], $assignment);

            } catch (\Exception $e) {
                $vendor = $assignment['organization'] ?? $assignment['registry'] ?? "UNKNOWN";
                $errors[] = "Error inserting vendor $vendor";
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        foreach ($errors as $error) {
            $this->warn($error);
        }
    }
}
