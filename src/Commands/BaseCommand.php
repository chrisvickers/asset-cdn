<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 01.03.2018
 * Time: 18:51.
 */

namespace Arubacao\AssetCdn\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\SplFileInfo;

abstract class BaseCommand extends Command
{

    const VERSION_PATH = 'version-path';

    /**
     * @param \Symfony\Component\Finder\SplFileInfo[] $files
     * @return array
     */
    protected function mapToPathname(array $files): array
    {
        return array_map(function (SplFileInfo $file) {
            return $file->getRelativePathname();
        }, $files);
    }


    public function isUsingVersion() : bool
    {
        return $this->hasOption(static::VERSION_PATH) && $this->option(static::VERSION_PATH);
    }


    public function version() : string|null
    {
        if(!$this->isUsingVersion()) {
            return null;
        }
        return $this->option(static::VERSION_PATH);
    }
}
