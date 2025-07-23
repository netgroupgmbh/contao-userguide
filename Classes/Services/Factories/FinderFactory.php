<?php

/**
 * @since       21.07.2025 - 13:02
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Factories;

use Symfony\Component\Finder\Finder;

class FinderFactory
{


    /**
     * @return Finder
     */
    public function createFinder(): Finder
    {
        return new Finder();
    }
}
