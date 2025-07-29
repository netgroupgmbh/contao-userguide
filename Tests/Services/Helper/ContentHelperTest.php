<?php

/**
 * @since       29.07.2025 - 10:54
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Services\Helper;

use Contao\CoreBundle\InsertTag\InsertTagParser;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Document;
use NetGroup\UserGuide\Classes\Services\Factories\MarkdownFactory;
use NetGroup\UserGuide\Classes\Services\Helper\ContentHelper;
use NetGroup\UserGuide\Classes\Services\Helper\QueryHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContentHelperTest extends TestCase
{


    /**
     * @var QueryHelper
     */
    private QueryHelper $queryHelper;


    /**
     * @var MarkdownFactory
     */
    private MarkdownFactory $markdownFactory;


    /**
     * @var Document|(Document&MockObject)|MockObject
     */
    private Document $document;


    /**
     * @var InsertTagParser
     */
    private InsertTagParser $insertTagParser;


    /**
     * @var ContentHelper
     */
    private ContentHelper $contentHelper;


    protected function setUp(): void
    {
        $this->queryHelper       = $this->getMockBuilder(QueryHelper::class)
                                        ->disableOriginalConstructor()
                                        ->getMock();

        $this->markdownFactory   = $this->getMockBuilder(MarkdownFactory::class)
                                        ->disableOriginalConstructor()
                                        ->getMock();

        $this->document          = $this->getMockBuilder(Document::class)
                                        ->disableOriginalConstructor()
                                        ->getMock();

        $this->insertTagParser   = $this->getMockBuilder(InsertTagParser::class)
                                        ->disableOriginalConstructor()
                                        ->getMock();

        $this->contentHelper     = new ContentHelper(
            $this->queryHelper,
            $this->markdownFactory,
            $this->insertTagParser
        );
    }


    /**
     * Testet die Rückgabe des konvertierten Inhalts inklusive InsertTag-Ersatz.
     */
    public function testGetContentReturnsProcessedString(): void
    {
        // Arrange
        $id             = 42;
        $rawContent     = 'Text mit {\{insert_tag::xyz}';
        $htmlContent    = '<p>Text mit {{insert_tag::xyz}}</p>';
        $finalContent   = '<p>Text mit REPLACED</p>';

        $this->queryHelper->method('loadContentFromGuide')
                          ->with($id)
                          ->willReturn($rawContent);

        $converterMock  = $this->getMockBuilder(MarkdownConverter::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $converterMock->method('convert')
                      ->with($rawContent)
                      ->willReturn(new \League\CommonMark\Output\RenderedContent($this->document, $htmlContent));

        $this->markdownFactory->method('createConverter')
                              ->willReturn($converterMock);

        $this->insertTagParser->method('replace')
                              ->with($htmlContent)
                              ->willReturn($finalContent);

        // Act
        $result = $this->contentHelper->getContent($id);

        // Assert
        $this->assertEquals($finalContent, $result);
    }


    /**
     * Testet, dass Escape-Sequenzen {\{ durch {{ ersetzt werden.
     */
    public function testGetContentReplacesEscapedInsertTagSyntax(): void
    {
        // Arrange
        $id             = 1;
        $input          = 'Some {\{tag}';
        $converted      = 'Some {{tag}}';
        $replaced       = 'Some {{tag}}';

        $this->queryHelper->method('loadContentFromGuide')
                          ->willReturn($input);

        $converterMock  = $this->getMockBuilder(MarkdownConverter::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $converterMock->method('convert')
                      ->willReturn(new \League\CommonMark\Output\RenderedContent($this->document, $converted));

        $this->markdownFactory->method('createConverter')
                              ->willReturn($converterMock);

        $this->insertTagParser->method('replace')
                              ->willReturn($replaced);

        // Act
        $result = $this->contentHelper->getContent($id);

        // Assert
        $this->assertEquals('Some {{tag}}', $result);
    }


    /**
     * Testet, ob der Vorschaulink korrekt in den Buffer eingesetzt wird.
     */
    public function testInsertBackLinkAddsLinkToBuffer(): void
    {
        // Arrange
        $manualId   = 3;
        $guideId    = 77;
        $buffer     = '<div id="tl_buttons">';

        $expected   = '<div id="tl_buttons">' . "\n" .
            '<a href="/contao?do=usersguide&id=3&table=tl_guides&key=renderguide&guide=77">' .
            '<i class="fa-solid fa-magnifying-glass"></i> Vorschau</a>';

        // Act
        $result = $this->contentHelper->insertBackLink($manualId, $guideId, $buffer);

        // Assert
        $this->assertEquals($expected, $result);
    }


    /**
     * Testet, dass der Buffer unverändert bleibt, wenn das Suchmuster nicht enthalten ist.
     */
    public function testInsertBackLinkWithoutPlaceholderReturnsOriginal(): void
    {
        // Arrange
        $manualId   = 1;
        $guideId    = 2;
        $buffer     = '<div>Kein Button</div>';

        // Act
        $result = $this->contentHelper->insertBackLink($manualId, $guideId, $buffer);

        // Assert
        $this->assertEquals($buffer, $result);
    }
}
