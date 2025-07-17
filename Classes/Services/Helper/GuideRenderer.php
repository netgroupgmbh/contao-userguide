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

use NetGroup\UserGuide\Classes\Services\Factories\MarkdownFactory;
use NetGroup\UserGuide\Classes\Services\Factories\TemplateFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class GuideRenderer
{


    public const string TEMPLATE_NAME = 'mod_guide';


    /**
     * @param RequestStack $requestStack
     * @param MarkdownFactory $markdownFactory
     * @param TemplateFactory $templateFactory
     * @param QueryHelper $queryHelper
     */
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly MarkdownFactory $markdownFactory,
        private readonly TemplateFactory $templateFactory,
        private readonly QueryHelper $queryHelper
    ) {
    }


    /**
     * Rendert den Inhalt einer Anleitung.
     *
     * @return string
     * @throws \League\CommonMark\Exception\CommonMarkException
     */
    public function render(): string
    {
        $request                = $this->requestStack->getCurrentRequest();
        $id                     = (int)$request?->get('guide');
        $content                = $this->queryHelper->loadContentFromGuide($id);
        $template               = $this->templateFactory->createBeackendTemplate(self::TEMPLATE_NAME);
        $template->content      = $this->markdownFactory->createConverter()->convert($content)->getContent();
        $template->manualId     = (int)$request?->get('id');

        return $template->parse();
    }
}
