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

use Contao\DataContainer;
use Contao\Image;

class TlManuals
{


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
    ): string {

        if (true === \is_array($operation)) {
            return sprintf(
                '<a href="%s" title="%s"%s>%s</a> ',
                \Backend::addToUrl($href . '&amp;id=' . $operation['id']),
                \StringUtil::specialchars($title),
                $attributes,
                Image::getHtml('editor.svg', $label)
            );
        }

        return '';
    }
}
