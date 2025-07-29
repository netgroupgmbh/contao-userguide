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

use Contao\Backend;
use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;
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
     * @param DataContainerOperation|array<string> $operation
     *
     * @return void
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
        // Nur in Contao >= 5.0
        if (false === \is_array($operation)) {
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

        // nur bei Contao 4 ausführen
        if (true === \is_array($operation)) {
            if (false === (bool) $operation['locked']
                || (
                    !empty($operation['pid'])
                    && false === $this->lockHelper->checkLocked($operation['pid'], TableNames::tl_manuals)
                )
            ) {
                return sprintf(
                    '<a href="%s" title="%s"%s>%s</a> ',
                    Backend::addToUrl($href . '&amp;id=' . $operation['id']),
                    StringUtil::specialchars($title),
                    $attributes,
                    Image::getHtml($icon, $label)
                );
            }
        }

        return '';
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
