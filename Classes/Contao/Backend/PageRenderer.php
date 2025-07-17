<?php

/**
 * @since       17.07.2025 - 11:55
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Contao\Backend;

use Contao\System;
use NetGroup\UserGuide\Classes\Services\Helper\GuideRenderer;

class PageRenderer
{


    /**
     * @return string
     */
    public function render(): string
    {
        $GLOBALS['TL_CSS'][] = 'bundles/netgroupuserguide/icons/fontawesome-free-6.6.0-web/css/all.css';
        $GLOBALS['TL_CSS'][] = 'bundles/netgroupuserguide/css/markdown.css';

        return System::getContainer()->get(GuideRenderer::class)?->render();
    }
}
