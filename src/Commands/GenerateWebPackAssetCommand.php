<?php

namespace Arubacao\AssetCdn\Commands;

use Arubacao\AssetCdn\Finder;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\FilesystemManager;

class GenerateWebPackAssetCommand extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset-cdn:generate:webpack {file-location} {version-path}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Webpack Asset File from CDN';


    /**
     * @var string
     */
    private $filesystem;

    /**
     * @var FilesystemManager
     */
    private $filesystemManager;


    public function handle(FilesystemManager $filesystemManager, Repository $config) : void
    {
        $this->filesystem = $config->get('asset-cdn.filesystem.disk');
        $this->filesystemManager = $filesystemManager;

        $fileLocation = $this->argument('file-location');

        if (!file_exists($fileLocation)) {
            touch($fileLocation);
        }

        file_put_contents
        (
            $fileLocation,
            json_encode(
                $this->buildJsonFile(
                    $this->getVersion(),
                    $this->getFilesToWrite()
                )
            )
        );


    }



    private function getFilesToWrite() : array
    {
        return $this->filesystemManager
            ->disk($this->filesystem)
            ->allFiles($this->version());
    }

    protected function getVersion()
    {
        return $this->argument('version-path');
    }


    protected function buildJsonFile(string $version, array $files) : array
    {
        $dataToWrite = [
            'version-path'   =>  $this->getVersion()
        ];

        foreach ($files as $file) {
            $file = basename($file);
            $ext = pathinfo($file, PATHINFO_EXTENSION);



            $fileWithoutExtension = str_ireplace(".$ext", '', $file);
            $dataToWrite[$fileWithoutExtension][$ext] = $file;
        }

        return $dataToWrite;
    }



    /**
     * {
    "version-path" : "v23.02.01",
    "business": {
    "js": "/business-a880e71654d40e32.js",
    "css": "/business.bd3a43cefeaf2330.css"
    },
    "runtime": {
    "js": "/runtime-a880e71654d40e32.js"
    },
    "": {
    "ttf": "/f1a45d7466ff780d.ttf",
    "woff": "/ff18efd173a3b2c1.woff"
    }
    }

     */

}