<?php

/**
 * @since       29.07.2025 - 13:56
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Services\Helper;

use NetGroup\UserGuide\Classes\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Helper\ButtonHelper;
use NetGroup\UserGuide\Classes\Services\Helper\ContaoAdapter;
use NetGroup\UserGuide\Classes\Services\Helper\LockHelper;
use PHPUnit\Framework\TestCase;

class ButtonHelperTest extends TestCase
{


    /**
     * @var LockHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    private LockHelper $lockHelperMock;


    /**
     * @var ContaoAdapter&\PHPUnit\Framework\MockObject\MockObject
     */
    private ContaoAdapter $adapterMock;


    /**
     * @var ButtonHelper
     */
    private ButtonHelper $buttonHelper;


    protected function setUp(): void
    {
        $this->lockHelperMock	= $this->createMock(LockHelper::class);
        $this->adapterMock		= $this->createMock(ContaoAdapter::class);

        $this->buttonHelper = new ButtonHelper($this->lockHelperMock, $this->adapterMock);
    }


    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function testHandelButtonInCto4ReturnsLinkWhenNotLocked(): void
    {
        $row = [
            'id'	 => 7,
            'pid'	 => 9,
            'locked' => false,
        ];

        $this->lockHelperMock
            ->method('checkLocked')
            ->with(9, TableNames::tl_manuals)
            ->willReturn(false);

        $this->adapterMock
            ->method('addToUrl')
            ->willReturnCallback(static fn ($url) => $url);

        $this->adapterMock
            ->method('specialchars')
            ->willReturnCallback(static fn ($text) => $text);

        $this->adapterMock
            ->method('getHtml')
            ->willReturnCallback(static fn ($icon, $label) => $label);

        $result = $this->buttonHelper->handelButtonInCto4(
            $row,
            'edit.php',
            'Bearbeiten',
            'Datensatz bearbeiten',
            ' class="edit"',
            'icon.svg'
        );

        $this->assertStringContainsString('<a href="edit.php&amp;id=7"', $result);
        $this->assertStringContainsString('Datensatz bearbeiten', $result);
        $this->assertStringContainsString('Bearbeiten</a>', $result);
    }


    /**
     * Prüft, ob handelButtonInCto4 eine leere Zeichenkette zurückgibt, wenn Lock aktiv ist.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testHandelButtonInCto4ReturnsEmptyStringWhenLocked(): void
    {
        $row = [
            'id'	 => 7,
            'pid'	 => 9,
            'locked' => true,
        ];

        $this->lockHelperMock
            ->method('checkLocked')
            ->with(9, TableNames::tl_manuals)
            ->willReturn(true);

        $result = $this->buttonHelper->handelButtonInCto4(
            $row,
            'edit.php',
            'Bearbeiten',
            'Datensatz bearbeiten',
            ' class="edit"',
            'icon.svg'
        );

        $this->assertSame('', $result);
    }
}
