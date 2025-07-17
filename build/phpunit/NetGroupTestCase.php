<?php

/**
 * @since       17.07.2025 - 08:33
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide;

use Contao\TestCase\ContaoTestCase;

class NetGroupTestCase extends ContaoTestCase
{


    /**
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }


    /**
     * setup the environment
     */
    protected function setUp(): void
    {
    }


    /**
     * tear down the environment
     */
    protected function tearDown(): void
    {
    }


    /**
     * Ersetzt withConsecutive()
     * @param array ...$args
     * @return array
     * @see https://gist.github.com/ziadoz/370fe63e24f31fd1eb989e7477b9a472
     *
     * @example
     * $mock = $this->getMockBuilder(SomeClass::class)->getMock();
     *
     * $mock->expects($this->exactly(4))
     *      ->method('add')
     *      ->with(... $this->consecutiveParams(
     *          ['meta'],
     *          ['title'],
     *          ['caption'],
     *          ['alt']
     *      ))
     *      ->willReturnOnConsecutiveCalls(
     *          $meta,
     *          '',
     *          '',
     *          ''
     *      );
     */
    public function consecutiveParams(array ...$args): array
    {
        $callbacks = [];
        $count = count(max($args));

        for ($index = 0; $index < $count; $index++) {
            $returns = [];

            foreach ($args as $arg) {
                if (! array_is_list($arg)) {
                    throw new \InvalidArgumentException('Every array must be a list');
                }

                if (! isset($arg[$index])) {
                    throw new \InvalidArgumentException(sprintf('Every array must contain %d parameters', $count));
                }

                $returns[] = $arg[$index];
            }

            $callbacks[] = $this->callback(new class ($returns) {
                public function __construct(protected array $returns)
                {
                }

                public function __invoke(mixed $actual): bool
                {
                    if (count($this->returns) === 0) {
                        return true;
                    }

                    $next = array_shift($this->returns);
                    if ($next instanceof Constraint) {
                        $next->evaluate($actual);
                        return true;
                    }

                    return $actual === $next;
                }
            });
        }

        return $callbacks;
    }
}

