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

use Contao\Backend;
use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;

class TlManuals
{


    /**
     * @param string $projectRoot
     */
    public function __construct(private readonly string $projectRoot)
    {
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
        if (false === \is_file($this->projectRoot . '/system/themes/flexible/icons/children.svg')) {
            $icon = 'editor.svg';
        }

        return sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            Backend::addToUrl($href . '&amp;id=' . $operation['id']),
            StringUtil::specialchars($title),
            $attributes,
            Image::getHtml($icon, $label)
        );
    }
}
