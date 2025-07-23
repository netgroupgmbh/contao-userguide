<?php

/**
 * @since       22.07.2025 - 15:11
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Helper;

use NetGroup\UserGuide\Classes\Enums\TableNames;

class TableMatcher
{


    /**
     * Gibt ein TableNames-Objekt zu dem übergebenen Tabellennamen zurück.
     *
     * @param string $tableName
     *
     * @return ?TableNames
     */
    public function getTableFromString(string $tableName): ?TableNames
    {
        return match ($tableName) {
            TableNames::tl_guides->name             => TableNames::tl_guides,
            TableNames::tl_manual_categories->name  => TableNames::tl_manual_categories,
            TableNames::tl_manuals->name            => TableNames::tl_manuals,
            default                                 => null
        };
    }
}
