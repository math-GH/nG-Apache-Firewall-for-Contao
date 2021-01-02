<?php

declare(strict_types=1);

/*
 * This file is part of nG Apache Firewall.
 * 
 * (c) mathContao 2021 <geheim@web.de>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/mathcontao/nG-Apache-Firewall-for-contao
 */

namespace Mathcontao\NGApacheFirewallForContao\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class MathcontaoNGApacheFirewallForContaoExtension
 */
class MathcontaoNGApacheFirewallForContaoExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('parameters.yml');
        $loader->load('services.yml');
        $loader->load('listener.yml');
    }
}
