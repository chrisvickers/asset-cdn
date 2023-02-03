<?php

namespace Arubacao\AssetCdn\Test\Commands;

use Illuminate\Support\Facades\Artisan;

class GenerateWebPackAssetCommandTest extends TestCase
{
    protected $file_path;


    public function setUp(): void
    {
        parent::setUp();
        $this->file_path = public_path('webpack.json');
    }

    /** @test */
    public function a_webpack_file_is_generated()
    {
        Artisan::call('asset-cdn:generate:webpack testing');

        $this->assertFileExists($this->file_path);
    }

    /** @test */
    public function a_webpack_file_has_a_version_identified()
    {
        Artisan::call('asset-cdn:generate:webpack testing');

        $this->assertFileExists($this->file_path);

        $fileContents = json_decode(file_get_contents($this->file_path));

        $this->assertIsObject($fileContents);
        $this->assertObjectHasAttribute('version-path', $fileContents);
    }

    /** @test */
    public function a_webpack_file_has_css_files()
    {
        $this->setFilesInConfig([
            'extensions' => [
                'js',
                'css'
            ],
        ]);

        $this->seedCdnFilesystem([
            [
                'path' => 'js',
                'filename' => 'back.app.js',
            ],
            [
                'path' => 'css',
                'filename' => 'front.css',
            ],
            [
                'path' => 'css',
                'filename' => 'back.css',
            ],
        ]);

        Artisan::call('asset-cdn:generate:webpack testing');

        $this->assertFileExists($this->file_path);

        $fileContents = json_decode(file_get_contents($this->file_path), true);

        $this->assertTrue($fileContents['back']['css'] == 'back.css');
        $this->assertTrue($fileContents['front']['css'] == 'front.css');
        $this->assertTrue($fileContents['back.app']['js'] == 'back.app.js');
    }
}