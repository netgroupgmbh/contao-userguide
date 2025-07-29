<?php

/**
 * @since       29.07.2025 - 10:50
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Services\Helper;

use NetGroup\UserGuide\Classes\Services\Helper\AssetHelper;
use PHPUnit\Framework\TestCase;

class AssetHelperTest extends TestCase
{

    private AssetHelper $helper;


    protected function setUp(): void
    {
        $this->helper               = new AssetHelper();
        $GLOBALS['TL_CSS']          = null;
        $GLOBALS['TL_JAVASCRIPT']   = null;
    }


    public function testIncldueCss(): void
    {
        $this->assertEmpty($GLOBALS['TL_CSS']);
        $this->helper->incldueCss();
        $this->assertSame($this->helper::CSS, $GLOBALS['TL_CSS']);
    }


    public function testIncludeJavaScript(): void
    {
        $this->assertEmpty($GLOBALS['TL_JAVASCRIPT']);
        $this->helper->includeJavaScript();
        $this->assertSame($this->helper::JS, $GLOBALS['TL_JAVASCRIPT']);
    }
}
