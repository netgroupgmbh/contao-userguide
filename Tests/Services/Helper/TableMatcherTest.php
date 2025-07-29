<?php

/**
 * @since       29.07.2025 - 12:32
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
use NetGroup\UserGuide\Classes\Services\Helper\TableMatcher;
use PHPUnit\Framework\TestCase;

class TableMatcherTest extends TestCase
{


    /**
     * @var TableMatcher
     */
    private TableMatcher $matcher;


    protected function setUp(): void
    {
        $this->matcher = new TableMatcher();
    }


    public function testTableMatches(): void
    {
        $this->assertSame(
            TableNames::tl_guides,
            $this->matcher->getTableFromString(TableNames::tl_guides->name)
        );

        $this->assertSame(
            TableNames::tl_manual_categories,
            $this->matcher->getTableFromString(TableNames::tl_manual_categories->name)
        );

        $this->assertSame(
            TableNames::tl_manuals,
            $this->matcher->getTableFromString(TableNames::tl_manuals->name)
        );

        $this->assertNull($this->matcher->getTableFromString('tl_worng_table'));
    }
}
