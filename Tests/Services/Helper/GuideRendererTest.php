<?php

/**
 * @since       29.07.2025 - 11:12
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
use NetGroup\UserGuide\Classes\Services\Helper\GuideRenderer;
use NetGroup\UserGuide\Classes\Services\Helper\TableMatcher;
use NetGroup\UserGuide\Classes\Services\Helper\TemplateHelper;
use NetGroup\UserGuide\NetGroupTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class GuideRendererTest extends NetGroupTestCase
{


    /**
     * @var MockObject|(RequestStack&MockObject)
     */
    private $requestStack;


    /**
     * @var MockObject|(Request&MockObject)
     */
    private $request;


    /**
     * @var (TableMatcher&MockObject)|MockObject
     */
    private $tableMatcher;


    /**
     * @var (TemplateHelper&MockObject)|MockObject
     */
    private $templateHelper;


    /**
     * @var (BackendTemplate&MockObject)|MockObject
     */
    private $template;


    /**
     * @var GuideRenderer
     */
    private GuideRenderer $renderer;


    protected function setUp(): void
    {
        $this->requestStack     = $this->getMockBuilder(RequestStack::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->request          = $this->getMockBuilder(Request::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->tableMatcher     = $this->getMockBuilder(TableMatcher::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->templateHelper   = $this->getMockBuilder(TemplateHelper::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->template         = $this->getMockBuilder(BackendTemplate::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->renderer         = new GuideRenderer($this->requestStack, $this->tableMatcher, $this->templateHelper);
    }


    /**
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     * @throws \League\CommonMark\Exception\CommonMarkException
     */
    public function testRender(): void
    {
        $id         = 12;
        $table      = 'tl_test';
        $tableName  = TableNames::tl_guides;
        $content    = 'test';

        $this->requestStack->expects($this->once())
                           ->method('getCurrentRequest')
                           ->willReturn($this->request);

        $this->request->expects($this->exactly(2))
                      ->method('get')
                      ->with(...$this->consecutiveParams(
                          ['guide'],
                          ['table']
                      ))
                      ->willReturnOnConsecutiveCalls(
                          $id,
                          $table
                      );

        $this->tableMatcher->expects($this->once())
                           ->method('getTableFromString')
                           ->with($table)
                           ->willReturn($tableName);

        $this->templateHelper->expects($this->once())
                             ->method('getTemplateWithData')
                             ->with($id, $tableName, $this->request)
                             ->willReturn($this->template);

        $this->template->expects($this->once())
                       ->method('parse')
                       ->willReturn($content);

        $this->assertSame($content, $this->renderer->render());
    }
}
