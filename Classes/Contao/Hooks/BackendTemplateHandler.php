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

use NetGroup\UserGuide\Classes\Services\Helper\QueryHelper;
use Symfony\Component\HttpFoundation\RequestStack;

class BackendTemplateHandler
{


    /**
     * @param RequestStack $requestStack
     * @param QueryHelper  $queryHelper
     */
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly QueryHelper $queryHelper
    ) {
    }


    /**
     * Fügt den Vorschaulink beim Bearbeiten einer Anleitung ein.
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
                $manualId   = $this->queryHelper->loadPidFromGuide($guideId);
                $link       = "/contao?do=usersguide&id=$manualId&table=tl_guides&key=renderguide&guide=$guideId";
                $search     = '<div id="tl_buttons">';
                $replace    = "$search\n";
                $replace   .= '<a href="' . $link . '">';
                $replace   .= '<i class="fa-solid fa-magnifying-glass"></i> Vorschau</a>';
                $buffer     = \str_replace($search, $replace, $buffer);
            }
        }


        return $buffer;
    }
}
