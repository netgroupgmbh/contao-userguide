<?php

/**
 * @since       17.07.2025 - 12:19
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Factories;

use Contao\BackendTemplate;

class TemplateFactory
{


    /**
     * Erstellt ein Backend Template.
     *
     * @param string $templateName
     *
     * @return BackendTemplate
     *
     * @codeCoverageIgnore
     */
    public function createBeackendTemplate(string $templateName): BackendTemplate
    {
        return new BackendTemplate($templateName);
    }
}
