<?php

/**
 * @since       29.07.2025 - 11:36
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
use NetGroup\UserGuide\Classes\Services\Helper\LockHelper;
use NetGroup\UserGuide\Classes\Services\Helper\QueryHelper;
use NetGroup\UserGuide\NetGroupTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class LockHelperTest extends NetGroupTestCase
{


    /**
     * @var (QueryHelper&MockObject)|MockObject
     */
    private $queryHelper;


    /**
     * @var LockHelper
     */
    private LockHelper $helper;


    protected function setUp(): void
    {
        $this->queryHelper  = $this->getMockBuilder(QueryHelper::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->helper       = new LockHelper($this->queryHelper);
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckLockedReturnFalseIfIdIsNull(): void
    {
        $id     = null;
        $table  = TableNames::tl_manuals;

        $this->queryHelper->expects($this->never())
                          ->method('loadLocked');

        $this->queryHelper->expects($this->never())
                          ->method('loadPidFromGuide');

        $this->assertFalse($this->helper->checkLocked($id, $table));
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckLockedReturnFalseIfParentIsNotLockedAndTableIsTlManualCategories(): void
    {
        $id     = 12;
        $pid    = 34;
        $table  = TableNames::tl_manual_categories;

        $this->queryHelper->expects($this->once())
                          ->method('loadPidFromGuide')
                          ->with($id)
                          ->willReturn("$pid");

        $this->queryHelper->expects($this->once())
                          ->method('loadLocked')
                          ->with($pid, TableNames::tl_manuals)
                          ->willReturn(false);

        $this->assertFalse($this->helper->checkLocked($id, $table));
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckLockedReturnFalseIfParentIsLockedAndTableIsTlManualCategories(): void
    {
        $id     = 12;
        $pid    = 34;
        $table  = TableNames::tl_manual_categories;

        $this->queryHelper->expects($this->once())
                          ->method('loadPidFromGuide')
                          ->with($id)
                          ->willReturn("$pid");

        $this->queryHelper->expects($this->once())
                          ->method('loadLocked')
                          ->with($pid, TableNames::tl_manuals)
                          ->willReturn(true);

        $this->assertTrue($this->helper->checkLocked($id, $table));
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckLockedReturnFalseIfParentIsLockedAndTableIsTlGuide(): void
    {
        $id     = 12;
        $pid    = 34;
        $table  = TableNames::tl_guides;

        $this->queryHelper->expects($this->once())
                          ->method('loadPidFromGuide')
                          ->with($id)
                          ->willReturn("$pid");

        $this->queryHelper->expects($this->once())
                          ->method('loadLocked')
                          ->with($pid, TableNames::tl_manuals)
                          ->willReturn(true);

        $this->assertTrue($this->helper->checkLocked($id, $table));
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckLockedReturnFalseIfTableIsTlManualCategories(): void
    {
        $id     = 12;
        $pid    = 34;
        $table  = TableNames::tl_manual_categories;

        $this->queryHelper->expects($this->once())
                          ->method('loadPidFromGuide')
                          ->with($id)
                          ->willReturn("$pid");

        $this->queryHelper->expects($this->once())
                          ->method('loadLocked')
                          ->with($pid, TableNames::tl_manuals)
                          ->willReturn(false);

        $this->assertFalse($this->helper->checkLocked($id, $table));
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckLockedReturnFalseIfTableIsTlGuidesAndNotLocked(): void
    {
        $id     = 12;
        $pid    = 34;
        $table  = TableNames::tl_guides;

        $this->queryHelper->expects($this->once())
                          ->method('loadPidFromGuide')
                          ->with($id)
                          ->willReturn("$pid");

        $this->queryHelper->expects($this->exactly(2))
                          ->method('loadLocked')
                          ->with(...$this->consecutiveParams(
                              [$pid, TableNames::tl_manuals],
                              [$id, $table]
                          ))
                          ->willReturn(false);

        $this->assertFalse($this->helper->checkLocked($id, $table));
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckLockedReturnTrueIfTableIsTlGuidesAndLocked(): void
    {
        $id     = 12;
        $pid    = 34;
        $table  = TableNames::tl_guides;

        $this->queryHelper->expects($this->once())
                          ->method('loadPidFromGuide')
                          ->with($id)
                          ->willReturn("$pid");

        $this->queryHelper->expects($this->exactly(2))
                          ->method('loadLocked')
                          ->with(...$this->consecutiveParams(
                              [$pid, TableNames::tl_manuals],
                              [$id, $table]
                          ))
                          ->willReturnOnConsecutiveCalls(
                              false,
                              true
                          );

        $this->assertTrue($this->helper->checkLocked($id, $table));
    }
}
