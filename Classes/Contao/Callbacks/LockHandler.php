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
use NetGroup\UserGuide\Classes\Services\Helper\ButtonHelper;
use NetGroup\UserGuide\Classes\Services\Helper\LockHelper;
use NetGroup\UserGuide\Classes\Services\Helper\TableMatcher;

class LockHandler
{


    /**
     * @param LockHelper   $lockHelper
     * @param TableMatcher $tableMatcher
     * @param ButtonHelper $buttonHelper
     */
    public function __construct(
        private readonly LockHelper $lockHelper,
        private readonly TableMatcher $tableMatcher,
        private readonly ButtonHelper $buttonHelper
    ) {
    }


    /**
     * button_callback: Disabled die Buttons, wenn der Datensatz gesperrt ist.
     *
     * @param array|DataContainerOperation $operation
     * @param string|null                  $href
     * @param string                       $label
     * @param string                       $title
     * @param string|null                  $icon
     * @param string                       $attributes
     * @param string                       $table
     * @param array                        $rootRecordIds
     * @param array|null                   $childRecordIds
     * @param bool                         $circularReference
     * @param string|null                  $previous
     * @param string|null                  $next
     * @param DataContainer                $dc
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function adjustOperations(
        array|DataContainerOperation $operation,
        ?string $href,
        string $label,
        string $title,
        ?string $icon,
        string $attributes,
        string $table,
        array $rootRecordIds,
        ?array $childRecordIds,
        bool $circularReference,
        ?string $previous,
        ?string $next,
        DataContainer $dc
    ): string {
        if (true === \is_a($operation, DataContainerOperation::class) && \method_exists($operation, 'disable')) {
            // Nur in Contao >= 5.0
            $this->buttonHelper->handleButton($operation);

            return '';
        }

        return $this->buttonHelper->handelButtonInCto4($operation, $href, $label, $title, $attributes, $icon);
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
