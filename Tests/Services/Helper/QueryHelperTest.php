<?php

/**
 * @since       29.07.2025 - 11:54
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Services\Helper;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use NetGroup\UserGuide\Classes\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Helper\QueryHelper;
use PHPUnit\Framework\TestCase;

class QueryHelperTest extends TestCase
{


    /**
     * @var Connection
     */
    private Connection $connection;


    /**
     * @var QueryBuilder
     */
    private QueryBuilder $queryBuilder;


    /**
     * @var Result
     */
    private Result $result;


    /**
     * @var QueryHelper
     */
    private QueryHelper $queryHelper;


    protected function setUp(): void
    {
        $this->connection       = $this->getMockBuilder(Connection::class)
                                       ->disableOriginalConstructor()
                                       ->onlyMethods(['createQueryBuilder'])
                                       ->getMock();

        $this->queryBuilder     = $this->getMockBuilder(QueryBuilder::class)
                                       ->disableOriginalConstructor()
                                       ->onlyMethods([
                                           'select',
                                           'from',
                                           'where',
                                           'setParameter',
                                           'executeQuery',
                                           'fetchAllAssociative',
                                           'fetchFirstColumn'
                                       ])
                                       ->getMock();

        $this->result           = $this->getMockBuilder(Result::class)
                                       ->disableOriginalConstructor()
                                       ->onlyMethods(['fetchAllAssociative', 'fetchFirstColumn'])
                                       ->getMock();

        $this->connection->method('createQueryBuilder')
                         ->willReturn($this->queryBuilder);

        $this->queryHelper      = new QueryHelper($this->connection);
    }


    /**
     * Testet das Laden der Kategorien nach PID.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadCategoriesFromPidReturnsResults(): void
    {
        // Arrange
        $expected   = [['id' => 1, 'title' => 'Test']];
        $pid        = 5;

        $this->queryBuilder->method('select')->with('*')->willReturnSelf();
        $this->queryBuilder->method('from')->with(TableNames::tl_manual_categories->name)->willReturnSelf();
        $this->queryBuilder->method('where')->with('pid = :pid', $pid)->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('pid', $pid)->willReturnSelf();
        $this->queryBuilder->method('executeQuery')->willReturn($this->result);

        $this->result->method('fetchAllAssociative')->willReturn($expected);

        // Act
        $result = $this->queryHelper->loadCategoriesFromPid($pid);

        // Assert
        $this->assertEquals($expected, $result);
    }


    /**
     * Testet das Laden eines einzelnen Feldes mit vorhandenem Ergebnis.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadFieldFromTableReturnsValueIfExists(): void
    {
        // Arrange
        $expected   = ['Hello'];
        $id         = 42;
        $field      = 'title';
        $table      = TableNames::tl_guides;

        $this->queryBuilder->method('select')->with($field)->willReturnSelf();
        $this->queryBuilder->method('from')->with($table->name)->willReturnSelf();
        $this->queryBuilder->method('where')->with('id = :id')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('id', $id)->willReturnSelf();
        $this->queryBuilder->method('executeQuery')->willReturn($this->result);

        $this->result->method('fetchFirstColumn')->willReturn($expected);

        // Act
        $result = $this->queryHelper->loadFieldFromTable($id, $field, $table);

        // Assert
        $this->assertEquals('Hello', $result);
    }


    /**
     * Testet das Laden eines Feldes mit leerem Ergebnis.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadFieldFromTableReturnsEmptyStringIfEmpty(): void
    {
        // Arrange
        $id         = 12;
        $field      = 'foo';
        $table      = TableNames::tl_guides;

        $this->queryBuilder->method('select')->with($field)->willReturnSelf();
        $this->queryBuilder->method('from')->with($table->name)->willReturnSelf();
        $this->queryBuilder->method('where')->with('id = :id')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('id', $id)->willReturnSelf();
        $this->queryBuilder->method('executeQuery')->willReturn($this->result);

        $this->result->method('fetchFirstColumn')->willReturn([]);

        // Act
        $result = $this->queryHelper->loadFieldFromTable($id, $field, $table);

        // Assert
        $this->assertEquals('', $result);
    }


    /**
     * Testet das Laden des Inhalts einer Anleitung.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadContentFromGuide(): void
    {
        // Arrange
        $id         = 77;
        $expected   = ['Inhalt'];

        $this->queryBuilder->method('select')->with('content')->willReturnSelf();
        $this->queryBuilder->method('from')->with(TableNames::tl_guides->name)->willReturnSelf();
        $this->queryBuilder->method('where')->with('id = :id')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('id', $id)->willReturnSelf();
        $this->queryBuilder->method('executeQuery')->willReturn($this->result);

        $this->result->method('fetchFirstColumn')->willReturn($expected);

        // Act
        $result = $this->queryHelper->loadContentFromGuide($id);

        // Assert
        $this->assertEquals('Inhalt', $result);
    }


    /**
     * Testet das Laden der PID einer Anleitung.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadPidFromGuide(): void
    {
        // Arrange
        $id         = 22;
        $expected   = ['7'];

        $this->queryBuilder->method('select')->with('pid')->willReturnSelf();
        $this->queryBuilder->method('from')->with(TableNames::tl_guides->name)->willReturnSelf();
        $this->queryBuilder->method('where')->with('id = :id')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('id', $id)->willReturnSelf();
        $this->queryBuilder->method('executeQuery')->willReturn($this->result);

        $this->result->method('fetchFirstColumn')->willReturn($expected);

        // Act
        $result = $this->queryHelper->loadPidFromGuide($id);

        // Assert
        $this->assertEquals('7', $result);
    }


    /**
     * Testet das Laden des Lock-Status einer Anleitung als bool.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadLockedReturnsFalse(): void
    {
        // Arrange
        $id         = 100;
        $table      = TableNames::tl_guides;

        $this->queryBuilder->method('select')->with('locked')->willReturnSelf();
        $this->queryBuilder->method('from')->with($table->name)->willReturnSelf();
        $this->queryBuilder->method('where')->with('id = :id')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('id', $id)->willReturnSelf();
        $this->queryBuilder->method('executeQuery')->willReturn($this->result);

        // FALSE-Test
        $this->result->method('fetchFirstColumn')->willReturn(['0']);
        $this->assertFalse($this->queryHelper->loadLocked($id, $table));
    }


    /**
     * Testet das Laden des Lock-Status einer Anleitung als bool.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadLockedReturnsTrue(): void
    {
        // Arrange
        $id         = 100;
        $table      = TableNames::tl_guides;

        $this->queryBuilder->method('select')->with('locked')->willReturnSelf();
        $this->queryBuilder->method('from')->with($table->name)->willReturnSelf();
        $this->queryBuilder->method('where')->with('id = :id')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('id', $id)->willReturnSelf();
        $this->queryBuilder->method('executeQuery')->willReturn($this->result);

        // TRUE-Test
        $this->result->method('fetchFirstColumn')->willReturn(['1']);
        $this->assertTrue($this->queryHelper->loadLocked($id, $table));
    }
}
