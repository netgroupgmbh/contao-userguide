<?php

/**
 * @since       29.07.2025 - 13:13
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Contao\Hooks;

use NetGroup\UserGuide\Classes\Contao\Hooks\BackendTemplateHandler;
use NetGroup\UserGuide\Classes\Services\Helper\ContentHelper;
use NetGroup\UserGuide\Classes\Services\Helper\QueryHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BackendTemplateHandlerTest extends TestCase
{


    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;


    /**
     * @var QueryHelper
     */
    private QueryHelper $queryHelper;


    /**
     * @var ContentHelper
     */
    private ContentHelper $contentHelper;


    /**
     * @var BackendTemplateHandler
     */
    private BackendTemplateHandler $handler;


    protected function setUp(): void
    {
        $this->requestStack     = $this->createMock(RequestStack::class);
        $this->queryHelper      = $this->createMock(QueryHelper::class);
        $this->contentHelper    = $this->createMock(ContentHelper::class);
        $this->handler          = new BackendTemplateHandler(
            $this->requestStack,
            $this->queryHelper,
            $this->contentHelper
        );
    }


    /**
     * Testet den Fall, wenn kein Request vorhanden ist. Es darf keine Änderung am Buffer erfolgen.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testInsertPreviewLinkWithoutRequest(): void
    {
        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn(null);

        $buffer   = 'original content';
        $template = 'be_main';

        $result = $this->handler->insertPreviewLink($buffer, $template);

        $this->assertSame($buffer, $result);
    }


    /**
     * Testet den Fall, wenn die URL nicht alle erwarteten Parameter enthält. Buffer bleibt unverändert.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testInsertPreviewLinkWithIrrelevantUrl(): void
    {
        $request = $this->createMock(Request::class);

        $request
            ->method('getUri')
            ->willReturn('https://example.com/contao?do=article&table=tl_content');

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $buffer = '<div>Backend</div>';

        $result = $this->handler->insertPreviewLink($buffer, 'be_main');

        $this->assertSame($buffer, $result);
    }


    /**
     * Testet insertPreviewLink mit vollständigen Parametern in der URL, erwartet Aufruf von insertBackLink().
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testInsertPreviewLinkWithMatchingUrl(): void
    {
        $request = $this->createMock(Request::class);

        $request
            ->method('getUri')
            ->willReturn('https://example.com/contao?do=usersguide&table=tl_guides&act=edit&id=42');

        $request
            ->method('get')
            ->with('id')
            ->willReturn('42');

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->queryHelper
            ->expects($this->once())
            ->method('loadPidFromGuide')
            ->with(42)
            ->willReturn('11');

        $this->contentHelper
            ->expects($this->once())
            ->method('insertBackLink')
            ->with('11', 42, 'original buffer')
            ->willReturn('buffer with backlink');

        $result = $this->handler->insertPreviewLink('original buffer', 'be_main');

        $this->assertSame('buffer with backlink', $result);
    }


    /**
     * Testet insertPreviewLink mit URI und fehlender id. Es soll kein insertBackLink erfolgen.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testInsertPreviewLinkWithMissingId(): void
    {
        $request = $this->createMock(Request::class);

        $request
            ->method('getUri')
            ->willReturn('https://example.com/contao?do=usersguide&table=tl_guides&act=edit');

        $request
            ->method('get')
            ->with('id')
            ->willReturn(null);

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->queryHelper
            ->expects($this->once())
            ->method('loadPidFromGuide')
            ->with(0)
            ->willReturn('0');

        $this->contentHelper
            ->expects($this->once())
            ->method('insertBackLink')
            ->with('0', 0, 'base')
            ->willReturn('inserted');

        $result = $this->handler->insertPreviewLink('base', 'be_main');

        $this->assertSame('inserted', $result);
    }
}
