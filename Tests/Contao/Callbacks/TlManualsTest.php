<?php

/**
 * @since       29.07.2025 - 14:30
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Contao\Callbacks;

use Contao\DataContainer;
use NetGroup\UserGuide\Classes\Contao\Callbacks\TlManuals;
use NetGroup\UserGuide\Classes\Services\Factories\FinderFactory;
use NetGroup\UserGuide\Classes\Services\Helper\ContaoAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class TlManualsTest extends TestCase
{


    /**
     * @var string
     */
    private string $projectRoot;



    /**
     * @var ContaoAdapter&MockObject
     */
    private ContaoAdapter $adapterMock;



    /**
     * @var FinderFactory&MockObject
     */
    private FinderFactory $finderFactoryMock;



    /**
     * @var Filesystem&MockObject
     */
    private Filesystem $filesystemMock;



    /**
     * @var DataContainer&MockObject
     */
    private DataContainer $dataContainerMock;



    protected function setUp(): void
    {
        $this->projectRoot       = '/project';
        $this->adapterMock       = $this->createMock(ContaoAdapter::class);
        $this->finderFactoryMock = $this->createMock(FinderFactory::class);
        $this->filesystemMock    = $this->createMock(Filesystem::class);
        $this->dataContainerMock = $this->createMock(DataContainer::class);

        $this->finderFactoryMock
            ->method('createFileSystem')
            ->willReturn($this->filesystemMock);
    }



    /**
     * Testet adjustIcon, wenn die Icon-Datei vorhanden ist.
     */
    public function testAdjustIconWithExistingFile(): void
    {
        // Arrange
        $this->filesystemMock
            ->method('exists')
            ->willReturn(true);

        $operation = ['id' => 101];
        $href      = 'do=test';
        $label     = 'Label';
        $title     = 'Titel';
        $icon      = 'children.svg';
        $attributes = ' class="link"';
        $table      = 'tl_manuals';
        $rootRecordIds = [];
        $childRecordIds = null;
        $circularReference = false;
        $previous = null;
        $next     = null;

        $expectedHref  = 'url?do=test&id=101';
        $expectedTitle = 'escaped-title';
        $expectedHtml  = '<img src="children.svg" alt="Label">';

        $this->adapterMock
            ->method('addToUrl')
            ->with($href . '&amp;id=101')
            ->willReturn($expectedHref);

        $this->adapterMock
            ->method('specialchars')
            ->with($title)
            ->willReturn($expectedTitle);

        $this->adapterMock
            ->method('getHtml')
            ->with($icon, $label)
            ->willReturn($expectedHtml);

        $tlManuals = new TlManuals(
            $this->projectRoot,
            $this->adapterMock,
            $this->finderFactoryMock
        );

        $expected = sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            $expectedHref,
            $expectedTitle,
            $attributes,
            $expectedHtml
        );

        // Act
        $result = $tlManuals->adjustIcon(
            $operation,
            $href,
            $label,
            $title,
            $icon,
            $attributes,
            $table,
            $rootRecordIds,
            $childRecordIds,
            $circularReference,
            $previous,
            $next,
            $this->dataContainerMock
        );

        // Assert
        $this->assertEquals($expected, $result);
    }



    /**
     * Testet adjustIcon, wenn die Icon-Datei nicht vorhanden ist.
     */
    public function testAdjustIconWithMissingFile(): void
    {
        // Arrange
        $this->filesystemMock
            ->method('exists')
            ->willReturn(false);

        $operation = ['id' => 202];
        $href      = 'do=other';
        $label     = 'Manual';
        $title     = 'Handbuch';
        $icon      = 'children.svg'; // Wird ersetzt durch editor.svg
        $attributes = ' class="edit"';
        $table      = 'tl_manuals';
        $rootRecordIds = [];
        $childRecordIds = null;
        $circularReference = false;
        $previous = null;
        $next     = null;

        $expectedHref  = 'url?do=other&id=202';
        $expectedTitle = 'escaped';
        $expectedHtml  = '<img src="editor.svg" alt="Manual">';

        $this->adapterMock
            ->method('addToUrl')
            ->with($href . '&amp;id=202')
            ->willReturn($expectedHref);

        $this->adapterMock
            ->method('specialchars')
            ->with($title)
            ->willReturn($expectedTitle);

        $this->adapterMock
            ->method('getHtml')
            ->with('editor.svg', $label)
            ->willReturn($expectedHtml);

        $tlManuals = new TlManuals(
            $this->projectRoot,
            $this->adapterMock,
            $this->finderFactoryMock
        );

        $expected = sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            $expectedHref,
            $expectedTitle,
            $attributes,
            $expectedHtml
        );

        // Act
        $result = $tlManuals->adjustIcon(
            $operation,
            $href,
            $label,
            $title,
            $icon,
            $attributes,
            $table,
            $rootRecordIds,
            $childRecordIds,
            $circularReference,
            $previous,
            $next,
            $this->dataContainerMock
        );

        // Assert
        $this->assertEquals($expected, $result);
    }
}
