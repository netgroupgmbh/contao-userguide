<?php

/**
 * @since       29.07.2025 - 12:38
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Services\Helper;

use Contao\BackendTemplate;
use NetGroup\UserGuide\Classes\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Factories\TemplateFactory;
use NetGroup\UserGuide\Classes\Services\Helper\ContentHelper;
use NetGroup\UserGuide\Classes\Services\Helper\LockHelper;
use NetGroup\UserGuide\Classes\Services\Helper\TemplateHelper;
use NetGroup\UserGuide\NetGroupTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TemplateHelperTest extends NetGroupTestCase
{


    /**
     * @var MockObject|TemplateFactory
     */
    private TemplateFactory|MockObject $templateFactory;


    /*#
     * @var ContentHelper|MockObject
     */
    private ContentHelper|MockObject $contentHelper;


    /**
     * @var LockHelper|MockObject
     */
    private LockHelper|MockObject $lockHelper;


    /**
     * @var MockObject|RequestStack
     */
    private RequestStack|MockObject $requestStack;


    /**
     * @var TemplateHelper
     */
    private TemplateHelper $helper;


    protected function setUp(): void
    {
        $this->templateFactory = $this->createMock(TemplateFactory::class);
        $this->contentHelper   = $this->createMock(ContentHelper::class);
        $this->lockHelper      = $this->createMock(LockHelper::class);
        $this->requestStack    = $this->createMock(RequestStack::class);

        $this->helper = new TemplateHelper(
            $this->templateFactory,
            $this->contentHelper,
            $this->lockHelper,
            $this->requestStack
        );
    }


    /**
     * Testet getTemplateWithData() mit einem gültigen Request und gesetzter Tabelle.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     * @throws \League\CommonMark\Exception\CommonMarkException
     */
    public function testGetTemplateWithDataWithTable(): void
    {
        $content            = 'Testinhalt';
        $manualId           = 12;
        $guideId            = 34;
        $locked             = true;
        $backendTemplate    = $this->createMock(BackendTemplate::class);

        $this->templateFactory->method('createBeackendTemplate')
                              ->with(TemplateHelper::TEMPLATE_NAME_MODULE)
                              ->willReturn($backendTemplate);

        $this->contentHelper->method('getContent')
                            ->with($guideId)
                            ->willReturn($content);

        $request = $this->createMock(Request::class);

        $request->method('get')
                ->with('id')
                ->willReturn($manualId);

        $this->lockHelper->method('checkLocked')
                         ->with($guideId, TableNames::tl_guides)
                         ->willReturn(false);

        $backendTemplate->expects($this->exactly(5))
                        ->method('__set')
                        ->with(...$this->consecutiveParams(
                            ['content', $content],
                            ['manualId', $manualId],
                            ['guideId', $guideId],
                            ['locked', $locked],
                            ['locked', !$locked]
                        ));

        $result = $this->helper->getTemplateWithData($guideId, TableNames::tl_guides, $request);

        $this->assertSame($backendTemplate, $result);
    }


    /**
     * Testet getTemplateWithData() ohne übergebenen Table-Wert.
     */
    public function testGetTemplateWithDataWithoutTable(): void
    {
        $content            = 'Testinhalt';
        $manualId           = 12;
        $guideId            = 34;
        $locked             = true;
        $backendTemplate    = $this->createMock(BackendTemplate::class);

        $this->templateFactory->method('createBeackendTemplate')
                              ->with(TemplateHelper::TEMPLATE_NAME_MODULE)
                              ->willReturn($backendTemplate);

        $this->contentHelper->method('getContent')
                            ->with($guideId)
                            ->willReturn($content);

        $request = $this->createMock(Request::class);

        $request->method('get')
                ->with('id')
                ->willReturn($manualId);

        $this->lockHelper->expects($this->never())
                         ->method('checkLocked');

        $backendTemplate->expects($this->exactly(4))
                        ->method('__set')
                        ->with(...$this->consecutiveParams(
                            ['content', $content],
                            ['manualId', $manualId],
                            ['guideId', $guideId],
                            ['locked', $locked]
                        ));

        $result = $this->helper->getTemplateWithData($guideId, null, $request);

        $this->assertSame($backendTemplate, $result);
    }


    /**
     * Testet getlabelForTlGuide() mit vorhandenem Request und Icon.
     */
    public function testGetLabelForTlGuideWithIcon(): void
    {
        $template = $this->createMock(BackendTemplate::class);

        $template
            ->expects($this->once())
            ->method('parse')
            ->willReturn('parsed template');

        $this->templateFactory
            ->method('createBeackendTemplate')
            ->with(TemplateHelper::TEMPLATE_NAME_LINK)
            ->willReturn($template);

        $request = $this->createMock(Request::class);
        $request
            ->method('getUri')
            ->willReturn('https://example.org/detail');

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $row = ['id' => 99, 'icon' => 'custom-icon'];
        $label = 'My Label';

        $result = $this->helper->getlabelForTlGuide($row, $label);

        $this->assertSame('parsed template', $result);
    }


    /**
     * Testet getlabelForTlGuide() mit leerem Icon, erwartet Standardwert.
     */
    public function testGetLabelForTlGuideWithEmptyIcon(): void
    {
        $template = $this->createMock(BackendTemplate::class);

        $template
            ->expects($this->once())
            ->method('parse')
            ->willReturn('template with default icon');

        $this->templateFactory
            ->method('createBeackendTemplate')
            ->willReturn($template);

        $request = $this->createMock(Request::class);
        $request
            ->method('getUri')
            ->willReturn('/my/url');

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $row = ['id' => 1, 'icon' => ''];
        $label = 'Label Fallback';

        $result = $this->helper->getlabelForTlGuide($row, $label);

        $this->assertSame('template with default icon', $result);
    }


    /**
     * Testet getlabelForTlGuide() mit fehlender ID oder fehlendem Request, Rückgabe des ursprünglichen Labels.
     */
    public function testGetLabelForTlGuideReturnsFallbackLabel(): void
    {
        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn(null);

        $row = ['id' => 55, 'icon' => 'fa-icon'];
        $label = 'Plain Label';

        $result = $this->helper->getlabelForTlGuide($row, $label);

        $this->assertSame('Plain Label', $result);
    }
}
