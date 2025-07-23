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

namespace NetGroup\UserGuide\Classes\Contao\Manager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use NetGroup\UserGuide\NetGroupUserGuideBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface, RoutingPluginInterface
{


    /**
     * @param ParserInterface $parser
     *
     * @return array|ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [BundleConfig::create(NetGroupUserGuideBundle::class)->setLoadAfter([ContaoCoreBundle::class])];
    }


    /**
     * @param LoaderInterface $loader
     * @param array           $managerConfig
     *
     * @return void
     *
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $path   = \str_replace('/Classes/Contao/Manager', '', __DIR__) . '/Resources/config';
        $files  = ['callbacks.yml', 'commands.yml', 'controller.yml', 'hooks.yml', 'listener.yml', 'services.yml'];

        foreach ($files as $file) {
            if (\is_file("$path/$file")) {
                $loader->load("$path/$file");
            }
        }
    }


    /**
     * @param LoaderResolverInterface $resolver
     * @param KernelInterface         $kernel
     *
     * @return mixed|\Symfony\Component\Routing\RouteCollection|null
     *
     * @throws \Exception
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel): mixed
    {
        // $file = '@NetGroupContaoSsoBundle/Resources/config/routing.yml'; // funktioniert unter CTO 4.13 nicht!
        $file = \str_replace('/Classes/Contao/Manager', '', __DIR__) . '/Resources/config/routing.yml';

        if (false === \file_exists($file)) {
            return null;
        }

        return $resolver->resolve($file)->load($file);
    }
}
