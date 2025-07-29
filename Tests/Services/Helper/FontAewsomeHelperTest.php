<?php

/**
 * @since       29.07.2025 - 11:04
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Services\Helper;

use NetGroup\UserGuide\Classes\Services\Factories\FinderFactory;
use NetGroup\UserGuide\Classes\Services\Helper\FontAewsomeHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class FontAewsomeHelperTest extends TestCase
{


    /**
     * @var FinderFactory
     */
    private FinderFactory $finderFactory;


    /**
     * @var Finder
     */
    private Finder $finder;


    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;


    /**
     * @var FontAewsomeHelper
     */
    private FontAewsomeHelper $helper;


    /**
     * @var string
     */
    private string $projectRoot;


    protected function setUp(): void
    {
        $this->finderFactory    = $this->getMockBuilder(FinderFactory::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->finder           = $this->getMockBuilder(Finder::class)
                                       ->disableOriginalConstructor()
                                       ->onlyMethods(['files', 'in', 'name', 'hasResults', 'getIterator'])
                                       ->getMock();

        $this->filesystem       = $this->getMockBuilder(Filesystem::class)
                                       ->disableOriginalConstructor()
                                       ->onlyMethods(['exists'])
                                       ->getMock();

        $this->finderFactory->method('createFinder')
                            ->willReturn($this->finder);

        $this->finderFactory->method('createFileSystem')
                            ->willReturn($this->filesystem);

        $this->projectRoot      = \sys_get_temp_dir() . '/fa_test';

        $this->helper           = new FontAewsomeHelper($this->projectRoot, $this->finderFactory);
    }


    /**
     * Testet, dass bei nicht vorhandenem Verzeichnis ein leeres Array zurückgegeben wird.
     */
    public function testCreateOptionsReturnsEmptyIfDirectoryDoesNotExist(): void
    {
        // Arrange
        $this->filesystem->method('exists')
                         ->with($this->projectRoot . FontAewsomeHelper::ICON_FOLDER)
                         ->willReturn(false);

        // Act
        $result = $this->helper->createOpteions();

        // Assert
        $this->assertEquals([], $result);
    }


    /**
     * Testet, dass bei vorhandenem Verzeichnis aber keinen SVG-Dateien ein leeres Array zurückgegeben wird.
     */
    public function testCreateOptionsReturnsEmptyIfNoFilesFound(): void
    {
        // Arrange
        $this->filesystem->method('exists')
                         ->willReturn(true);

        $this->finder->method('files')
                     ->willReturnSelf();

        $this->finder->method('in')
                     ->willReturnSelf();

        $this->finder->method('name')
                     ->willReturnSelf();

        $this->finder->method('hasResults')
                     ->willReturn(false);

        // Act
        $result = $this->helper->createOpteions();

        // Assert
        $this->assertEquals([], $result);
    }


    /**
     * Testet, dass SVG-Dateien korrekt in Optionen konvertiert werden.
     */
    public function testCreateOptionsReturnsIconOptionsIfFilesExist(): void
    {
        // Arrange
        $this->filesystem->method('exists')
                         ->willReturn(true);

        $dir            = $this->projectRoot . FontAewsomeHelper::ICON_FOLDER . '/solid';

        $file1          = $this->createMock(SplFileInfo::class);
        $file1->method('getPath')->willReturn($dir);
        $file1->method('getBasename')->with('.svg')->willReturn('circle');

        $file2          = $this->createMock(SplFileInfo::class);
        $file2->method('getPath')->willReturn($dir);
        $file2->method('getBasename')->with('.svg')->willReturn('star');

        $this->finder->method('files')->willReturnSelf();
        $this->finder->method('in')->willReturnSelf();
        $this->finder->method('name')->willReturnSelf();
        $this->finder->method('hasResults')->willReturn(true);
        $this->finder->method('getIterator')->willReturn(new \ArrayIterator([$file1, $file2]));

        // Act
        $result = $this->helper->createOpteions();

        // Assert
        $expected = [
            'fa-solid fa-circle'   => 'solid: circle',
            'fa-solid fa-star'     => 'solid: star',
        ];

        $this->assertEquals($expected, $result);
    }
}
