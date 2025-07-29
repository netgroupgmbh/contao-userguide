<?php

/**
 * @since       29.07.2025 - 13:49
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Helper;

use Contao\Backend;
use Contao\Image;
use Contao\StringUtil;

class ContaoAdapter
{


    /**
     * @param string $query
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function addToUrl(string $query): string
    {
        return Backend::addToUrl($query);
    }


    /**
     * @param string $title
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function specialchars(string $title): string
    {
        return StringUtil::specialchars($title);
    }


    /**
     * @param string $icon
     * @param string $label
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getHtml(string $icon, string $label): string
    {
        return Image::getHtml($icon, $label);
    }
}
