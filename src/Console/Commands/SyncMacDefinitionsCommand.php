<?php

namespace Sealution\MacResolver\Console\Commands;

use Sealution\MacResolver\Exceptions\OuiFileNotFoundException;
use Sealution\MacResolver\OuiFile;
use Exception;
use Illuminate\Console\Command;

class SyncMacDefinitionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mac:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and store the OUI data from the IEEE web page.';
    

    /**
     * Execute the console command.
     *
     * @throws OuiFileNotFoundException
     * @throws Exception
     *
     * @return int
     */
    public function handle()
    {
        \Storage::deleteDirectory(OuiFile::FOLDER);
        
        $this->call(DownloadOuiFileFromIeeeWebPage::class, ['--file' => 'All']);
        $this->call(SeedTableFromOuiFile::class, ['--file' => 'All']);
        
        $this->info('Synchronization completed.');

        return 0;
    }
}
