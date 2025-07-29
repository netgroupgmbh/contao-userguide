<?php

/**
 * @since       29.07.2025 - 09:54
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
use Contao\DataContainer;
use NetGroup\UserGuide\Classes\Services\Factories\FinderFactory;
use NetGroup\UserGuide\Classes\Services\Helper\ContaoAdapter;

class TlManuals
{


    /**
     * @param string        $projectRoot
     * @param ContaoAdapter $adapter
     * @param FinderFactory $finderFactory
     */
    public function __construct(
        private readonly string $projectRoot,
        private readonly ContaoAdapter $adapter,
        private readonly FinderFactory $finderFactory
    ) {
    }


    /**
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
     * @return string|null
     */
    public function adjustIcon(
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
    ): ?string {
        $fs = $this->finderFactory->createFileSystem();

        if (false === $fs->exists($this->projectRoot . '/system/themes/flexible/icons/children.svg')) {
            $icon = 'editor.svg';
        }

        return sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            $this->adapter->addToUrl($href . '&amp;id=' . $operation['id']),
            $this->adapter->specialchars($title),
            $attributes,
            $this->adapter->getHtml($icon, $label)
        );
    }
}
