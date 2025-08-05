<?php

/**
 * @since       22.07.2025 - 08:55
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Contao\Hooks;

use NetGroup\UserGuide\Classes\Services\Helper\ContentHelper;
use NetGroup\UserGuide\Classes\Services\Helper\QueryHelper;
use NetGroup\UserGuide\Classes\Services\Helper\TableMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

class BackendTemplateHandler
{


    /**
     * @param RequestStack  $requestStack
     * @param QueryHelper   $queryHelper
     * @param ContentHelper $contentHelper
     * @param TableMatcher  $tableMatcher
     */
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly QueryHelper $queryHelper,
        private readonly ContentHelper $contentHelper,
        private readonly TableMatcher $tableMatcher
    ) {
    }


    /**
     * outputBackendTemplate-Hook: Fügt den Vorschaulink beim Bearbeiten einer Anleitung ein.
     *
     * @param string $buffer
     * @param string $template
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function insertPreviewLink(string $buffer, string $template): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request) {
            $link = $request->getUri();

            if (true === \str_contains($link, 'do=usersguide')
                && true === \str_contains($link, 'table=tl_guides')
                && true === \str_contains($link, 'act=edit')
            ) {
                $guideId    = (int) $request?->get('id');
                $table      = $this->tableMatcher->getTableFromString('tl_guides');

                if (null !== $table) {
                    $manualId   = (int) $this->queryHelper->loadPidFromGuide($guideId, $table);
                    $buffer     = $this->contentHelper->insertBackLink($manualId, $guideId, $buffer);
                }
            }
        }


        return $buffer;
    }
}
