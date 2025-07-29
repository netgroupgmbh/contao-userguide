<?php

/**
 * @since       29.07.2025 - 14:01
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Contao\Callbacks;

use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\DataContainer;
use NetGroup\UserGuide\Classes\Contao\Callbacks\LockHandler;
use NetGroup\UserGuide\Classes\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Helper\ButtonHelper;
use NetGroup\UserGuide\Classes\Services\Helper\LockHelper;
use NetGroup\UserGuide\Classes\Services\Helper\TableMatcher;
use NetGroup\UserGuide\NetGroupTestCase;

class LockHandlerTest extends NetGroupTestCase
{


    /**
     * @var LockHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    private LockHelper $lockHelperMock;


    /**
     * @var TableMatcher&\PHPUnit\Framework\MockObject\MockObject
     */
    private TableMatcher $tableMatcherMock;


    /**
     * @var ButtonHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    private ButtonHelper $buttonHelperMock;


    /**
     * @var LockHandler
     */
    private LockHandler $lockHandler;


    protected function setUp(): void
    {
        $this->lockHelperMock	= $this->createMock(LockHelper::class);
        $this->tableMatcherMock	= $this->createMock(TableMatcher::class);
        $this->buttonHelperMock	= $this->createMock(ButtonHelper::class);

        $this->lockHandler      = new LockHandler(
            $this->lockHelperMock,
            $this->tableMatcherMock,
            $this->buttonHelperMock
        );
    }


    /**
     * Prüft, ob bei Contao 5 handleButton aufgerufen und ein leerer String zurückgegeben wird.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testAdjustOperationsInContao5CallsHandleButtonAndReturnsEmptyString(): void
    {
        $operationMock  = $this->createMock(DataContainerOperation::class);
        $dcMock	        = $this->createMock(DataContainer::class);

        $this->buttonHelperMock->expects($this->once())
                               ->method('handleButton')
                               ->with($operationMock);

        $result = $this->lockHandler->adjustOperations(
            $operationMock,
            'edit.php',
            'Bearbeiten',
            'Titel',
            'icon.svg',
            ' class="edit"',
            'tl_manuals',
            [],
            null,
            false,
            null,
            null,
            $dcMock
        );

        $this->assertSame('', $result);
    }


    /**
     * Prüft, ob bei Contao 4 handelButtonInCto4 aufgerufen und der Rückgabewert durchgereicht wird.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testAdjustOperationsInContao4CallsHandelButtonInCto4AndReturnsHtml(): void
    {
        $operationArray = ['id' => 1];
        $dcMock	        = $this->createMock(DataContainer::class);

        $this->buttonHelperMock->expects($this->once())
                               ->method('handelButtonInCto4')
                               ->with(
                                   $operationArray,
                                   'edit.php',
                                   'Bearbeiten',
                                   'Titel',
                                   ' class="edit"',
                                   'icon.svg'
                               )
            ->willReturn('<a href="#">Bearbeiten</a>');

        $result = $this->lockHandler->adjustOperations(
            $operationArray,
            'edit.php',
            'Bearbeiten',
            'Titel',
            'icon.svg',
            ' class="edit"',
            'tl_manuals',
            [],
            null,
            false,
            null,
            null,
            $dcMock
        );

        $this->assertSame('<a href="#">Bearbeiten</a>', $result);
    }


    /**
     * Prüft, ob eine AccessDeniedException geworfen wird, wenn der Datensatz gesperrt ist.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckDatasetLockedThrowsAccessDeniedExceptionIfLocked(): void
    {
        $id     = 12;
        $table  = TableNames::tl_manuals->name;
        $dc		= $this->getMockBuilder(DataContainer::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        $dc->expects($this->exactly(2))
           ->method('__get')
           ->with(...$this->consecutiveParams(
               ['table'],
               ['id']
           ))->willReturnOnConsecutiveCalls(
               $table,
               $id
           );

        $this->tableMatcherMock
            ->method('getTableFromString')
            ->with($table)
            ->willReturn(TableNames::tl_manuals);

        $this->lockHelperMock
            ->method('checkLocked')
            ->with($id, TableNames::tl_manuals)
            ->willReturn(true);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Dieser Datensatz kann nicht bearbeitet werden.');

        $this->lockHandler->checkDatasetLocked('someValue', $dc);
    }


    /**
     * Prüft, ob der ursprüngliche Wert zurückgegeben wird, wenn kein Lock vorliegt.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckDatasetLockedReturnsValueWhenNotLocked(): void
    {
        $id     = 12;
        $table  = TableNames::tl_manuals->name;
        $dc		= $this->getMockBuilder(DataContainer::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        $dc->expects($this->exactly(2))
           ->method('__get')
           ->with(...$this->consecutiveParams(
               ['table'],
               ['id']
           ))->willReturnOnConsecutiveCalls(
               $table,
               $id
           );

        $this->tableMatcherMock
            ->method('getTableFromString')
            ->with($table)
            ->willReturn(TableNames::tl_manuals);

        $this->lockHelperMock
            ->method('checkLocked')
            ->with($id, TableNames::tl_manuals)
            ->willReturn(false);

        $result = $this->lockHandler->checkDatasetLocked('original', $dc);

        $this->assertSame('original', $result);
    }
}
