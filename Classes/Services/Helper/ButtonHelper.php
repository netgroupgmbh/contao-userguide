<?php

/**
 * @since       29.07.2025 - 13:18
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

class ButtonHelper
{


    /**
     * @param LockHelper $lockHelper
     */
    public function __construct(private readonly LockHelper $lockHelper, private readonly ContaoAdapter $adapter)
    {
    }


    /**
     * Blendet eine Operation unter Contao 4 aus.
     *
     * @param array       $row
     * @param string      $href
     * @param string      $label
     * @param string      $title
     * @param string      $attributes
     * @param string|null $icon
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function handelButtonInCto4(
        array $row,
        string $href,
        string $label,
        string $title,
        string $attributes,
        ?string $icon
    ): string {
        $table  = TableNames::tl_manuals;
        $pid    = $row['pid'] ?? null;
        $locked = $row['locked'] ?? false;

        if (false === $locked || false === $this->lockHelper->checkLocked($pid, $table)
        ) {
            return sprintf(
                '<a href="%s" title="%s"%s>%s</a> ',
                $this->adapter->addToUrl($href . '&amp;id=' . $row['id']),
                $this->adapter->specialchars($title),
                $attributes,
                $this->adapter->getHtml($icon, $label)
            );
        }

        return '';
    }
}
