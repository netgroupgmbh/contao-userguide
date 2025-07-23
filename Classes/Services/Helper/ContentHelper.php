<?php

/**
 * @since       23.07.2025 - 12:54
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Helper;

use Contao\CoreBundle\InsertTag\InsertTagParser;
use Doctrine\DBAL\Exception as DbalException;
use League\CommonMark\Exception\CommonMarkException;
use NetGroup\UserGuide\Classes\Services\Factories\MarkdownFactory;

class ContentHelper
{


    /**
     * @param QueryHelper     $queryHelper
     * @param MarkdownFactory $markdownFactory
     * @param InsertTagParser $insertTagParser
     */
    public function __construct(
        private readonly QueryHelper $queryHelper,
        private readonly MarkdownFactory $markdownFactory,
        private readonly InsertTagParser $insertTagParser,
    ) {
    }


    /**
     * Gibt den Inahlt zurück.
     *
     * @param int $id
     *
     * @return string
     *
     * @throws DbalException
     * @throws CommonMarkException
     */
    public function getContent(int $id): string
    {
        $content                = $this->queryHelper->loadContentFromGuide($id);
        $content                = $this->markdownFactory->createConverter()->convert($content)->getContent();
        $content                = $this->insertTagParser->replace($content);

        return \str_replace('{\{', '{{', $content); // Schutz zur Ausgabe von InsertTags entfernen!
    }
}
