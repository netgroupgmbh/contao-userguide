<?php

/**
 * @since       29.07.2025 - 10:44
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Services\Factories;

use Contao\CoreBundle\InsertTag\InsertTagParser;
use NetGroup\UserGuide\Classes\Services\Factories\MarkdownFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class MarkdownFactoryTest extends TestCase
{


    /**
     * @var InsertTagParser
     */
    private InsertTagParser $insertTagParser;


    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;


    /**
     * @var MarkdownFactory
     */
    private MarkdownFactory $factory;


    protected function setUp(): void
    {
        $this->insertTagParser  = $this->getMockBuilder(InsertTagParser::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->requestStack     = $this->getMockBuilder(RequestStack::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->factory          = new MarkdownFactory($this->insertTagParser, $this->requestStack);
    }


    /**
     * Testet, ob ein MarkdownConverter erfolgreich erstellt wird.
     *
     * @return void
     */
    public function testCreateConverterReturnsMarkdownConverter(): void
    {
        // Arrange
        $request    = $this->getMockBuilder(Request::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $request->method('getHost')
                ->willReturn('example.com');

        $this->requestStack->method('getCurrentRequest')
                           ->willReturn($request);


        $this->assertNotNull($this->factory->createConverter());
    }
}
