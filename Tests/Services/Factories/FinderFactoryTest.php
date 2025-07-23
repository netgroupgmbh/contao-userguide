<?php

/**
 * @since       22.07.2025 - 11:33
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Services\Factories;

use NetGroup\UserGuide\Classes\Services\Factories\FinderFactory;
use PHPUnit\Framework\TestCase;

class FinderFactoryTest extends TestCase
{


    public function testCreateFinder(): void
    {
        $factory = new FinderFactory();
        $this->assertNotNull($factory->createFinder());
    }
}
