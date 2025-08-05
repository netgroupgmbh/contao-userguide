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

class ButtonHelper
{


    /**
     * @param LockHelper    $lockHelper
     * @param ContaoAdapter $adapter
     * @param TableMatcher  $matcher
     */
    public function __construct(
        private readonly LockHelper $lockHelper,
        private readonly ContaoAdapter $adapter,
        private readonly TableMatcher $matcher
    ) {
    }


    /**
     * Blendet eine Operation aus.
     *
     * @param array       $row
     * @param string      $tableName
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
    public function handelButton(
        array $row,
        string $tableName,
        string $href,
        string $label,
        string $title,
        string $attributes,
        ?string $icon
    ): string {
        $table  = $this->matcher->getTableFromString($tableName);
        $id     = $row['id'] ?? null;
        $locked = !empty($row['locked']);

        if (false === $locked
            && null !== $table
            && null !== $id
            && false === $this->lockHelper->checkLocked($id, $table)
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
