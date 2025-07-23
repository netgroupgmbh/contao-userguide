<?php

/**
 * @since       22.07.2025 - 14:35
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Contao\Callbacks;

use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\DataContainer;
use NetGroup\UserGuide\Classes\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Helper\LockHelper;
use NetGroup\UserGuide\Classes\Services\Helper\TableMatcher;

class LockHandler
{


    /**
     * @param LockHelper   $lockHelper
     * @param TableMatcher $tableMatcher
     */
    public function __construct(
        private readonly LockHelper $lockHelper,
        private readonly TableMatcher $tableMatcher
    ) {
    }


    /**
     * button_callback: Disabled die Buttons, wenn der Datensatz gesperrt ist.
     *
     * @param DataContainerOperation $operation
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function adjustOperations(DataContainerOperation $operation): void
    {
        $row = $operation->getRecord();

        if (true === (bool) $row['locked']
            || (
                !empty($row['pid'])
                && true === $this->lockHelper->checkLocked($row['pid'], TableNames::tl_manuals)
            )
        ) {
            $operation->disable();
        }
    }


    /**
     * load_callback: Verhindert das Bearbeiten, wenn der Datensatz gesperrt ist.
     *
     * @param mixed         $value
     * @param DataContainer $dc
     *
     * @return mixed
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function checkDatasetLocked(mixed $value, DataContainer $dc): mixed
    {
        $table = $this->tableMatcher->getTableFromString($dc->table);

        if (null === $table || true === $this->lockHelper->checkLocked((int) $dc->id, $table)) {
            throw new AccessDeniedException('Dieser Datensatz kann nicht bearbeitet werden.');
        }

        return $value;
    }
}
