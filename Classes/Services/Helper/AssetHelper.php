<?php

/**
 * @since       22.07.2025 - 09:51
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Helper;

class AssetHelper
{

    public const CSS = [
        'bundles/netgroupuserguide/icons/fontawesome-free-6.6.0-web/css/all.css',
        'bundles/netgroupuserguide/js/highlight/styles/default.css',
        'bundles/netgroupuserguide/css/markdown.css'
    ];


    public const JS = [
        'bundles/netgroupuserguide/js/highlight/highlight.min.js',
        'bundles/netgroupuserguide/js/netgroup/HighlighterInitializer.js'
    ];


    /**
     * Bindet das CSS ein.
     *
     * @return void
     */
    public function incldueCss(): void
    {
        foreach (self::CSS as $css) {
            $GLOBALS['TL_CSS'][] = $css;
        }
    }


    /**
     * Bindet das JavaScript ein.
     *
     * @return void
     */
    public function includeJavaScript(): void
    {
        foreach (self::JS as $js) {
            $GLOBALS['TL_JAVASCRIPT'][] = $js;
        }
    }
}
