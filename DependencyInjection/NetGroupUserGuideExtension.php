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

declare(strict_types = 1);

namespace NetGroup\UserGuide\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Yaml\Yaml;

class NetGroupUserGuideExtension extends Extension implements PrependExtensionInterface
{


    /**
     * Konfiguriert den Logger, damit die Konfiguration nicht in app/config/config.yml geschrieben werden muss.
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container): void
    {
        $pathForRelpace = '/DependencyInjection';
        $configFile     = '/Resources/config/logger.yml';
        $root           = str_replace($pathForRelpace, '', __DIR__);

        if (\is_file($root . '/' . $configFile)) {
            // Konfiguration aus Yaml-Datei laden
            $configs = Yaml::parseFile($root . $configFile);

            if (\is_array($configs)) {
                // Konfiguraionen verarbeiten
                foreach ($configs as $bundle => $config) {
                    $container->prependExtensionConfig($bundle, $config);
                }
            }
        }
    }


    /**
     * Lädt die Konfigurationen
     * @param array            $mergedConfig
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $mergedConfig, ContainerBuilder $container): void
    {
    }
}
