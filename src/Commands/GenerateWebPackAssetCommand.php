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
    protected $signature = 'asset-cdn:generate:webpack {version-path}';


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

        $fileLocation = config('asset-cdn.webpack.location');

        if(!$fileLocation) {
            $this->error('Webpack Location is not set.');
            return;
        }

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
        $files = $this->filesystemManager
            ->disk($this->filesystem)
            ->allFiles($this->version());

        $excluded = config('asset-cdn.webpack.exclude');
        $extensions = config('asset-cdn.webpack');

        return array_filter($files, function ($file) use ($excluded) {

            if(in_array(basename($file), $excluded['files'])) {
                return false;
            }

            if(in_array(pathinfo($file, PATHINFO_EXTENSION), $excluded['extensions'])) {
                return false;
            }

            return true;
        });
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
            $dataToWrite[$this->fileNameWithoutHashed($fileWithoutExtension, $ext)][$ext] = "/$file";
        }

        return $dataToWrite;
    }


    protected function fileNameWithoutHashed(string $filename, string $extension) : string
    {
        $extensionHashes = config('asset-cdn.webpack.hashes');

        $extensionSplit = $extensionHashes['extension'][$extension] ?? null;

        if(!$extensionSplit) {
            return $filename;
        }

        $explodedFileName = explode($extensionSplit, $filename);
        return $explodedFileName[0];
    }

}
