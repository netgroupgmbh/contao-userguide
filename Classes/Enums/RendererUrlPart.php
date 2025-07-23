<?php

/**
 * @since       17.07.2025 - 12:28
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Enums;

enum RendererUrlPart: string
{

    case key = 'renderguide';

    case manualId = 'id';

    case guideId = 'guide';
}
