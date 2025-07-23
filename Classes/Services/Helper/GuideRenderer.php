<?php

/**
 * @since       17.07.2025 - 12:01
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Helper;

use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class GuideRenderer
{


    /**
     * @param RequestStack   $requestStack
     * @param TableMatcher   $tableMatcher
     * @param TemplateHelper $templateHelper
     */
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly TableMatcher $tableMatcher,
        private readonly TemplateHelper $templateHelper
    ) {
    }


    /**
     * Rendert den Inhalt einer Anleitung.
     *
     * @return string
     *
     * @throws \League\CommonMark\Exception\CommonMarkException
     * @throws Exception
     */
    public function render(): string
    {
        $request                = $this->requestStack->getCurrentRequest();
        $id                     = (int) $request?->get('guide');
        $table                  = $this->tableMatcher->getTableFromString($request?->get('table'));
        $template               = $this->templateHelper->getTemplateWithData($id, $table, $request);

        return $template->parse();
    }
}
