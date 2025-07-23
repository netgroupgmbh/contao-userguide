<?php

/**
 * @since       17.07.2025 - 11:50
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Factories;

use Contao\CoreBundle\InsertTag\CommonMarkExtension;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\HttpFoundation\RequestStack;

class MarkdownFactory
{


    /**
     * @param InsertTagParser $parser
     * @param RequestStack    $requestStack
     */
    public function __construct(
        private readonly InsertTagParser $parser,
        private readonly RequestStack $requestStack
    ) {
    }


    /**
     * @return MarkdownConverter
     */
    public function createConverter(): MarkdownConverter
    {
        $request        = $this->requestStack->getCurrentRequest();
        $environment    = new Environment([
            'external_link' => [
                'internal_hosts'        => $request?->getHost() ?: 'localhost',
                'open_in_new_window'    => true,
                'html_class'            => 'external-link',
                'noopener'              => 'external',
                'noreferrer'            => 'external',
            ],
        ]);

        $environment->addExtension(new CommonMarkExtension($this->parser));
        $environment->addExtension(new CommonMarkCoreExtension());

        // Support GitHub flavoured Markdown (using the individual extensions because we
        // don't want the DisallowedRawHtmlExtension which is included by default)
        $environment->addExtension(new AutolinkExtension());
        $environment->addExtension(new StrikethroughExtension());
        $environment->addExtension(new TableExtension());
        $environment->addExtension(new TaskListExtension());

        // Automatically mark external links as such if we have a request
        $environment->addExtension(new ExternalLinkExtension());

        return new MarkdownConverter($environment);
    }
}
