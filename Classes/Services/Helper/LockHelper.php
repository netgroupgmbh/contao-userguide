<?php

/**
 * @since       22.07.2025 - 14:51
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

class LockHelper
{


    /**
     * @param QueryHelper $queryHelper
     */
    public function __construct(private readonly QueryHelper $queryHelper)
    {
    }


    /**
     * Prüft, ob ein Datensatz gesperrt ist.
     *
     * @param int|null   $id
     * @param TableNames $table
     *
     * @return bool
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function checkLocked(?int $id, TableNames $table): bool
    {
        if (null === $id) {
            return false;
        }

        if (true === $this->queryHelper->loadLocked($id, $table)) {
            return true;
        }

        if (TableNames::tl_guides === $table) {
            $pid = (int) $this->queryHelper->loadPidFromGuide($id);

            return $this->queryHelper->loadLocked($pid, TableNames::tl_manuals);
        }

        return false;
    }
}
