<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\JobBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Config\ContainerBuilder;
use Contao\ManagerPlugin\Config\ExtensionPluginInterface;
use HeimrichHannot\JobBundle\HeimrichHannotJobBundle;
use HeimrichHannot\UtilsBundle\Container\ContainerUtil;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface, ExtensionPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(HeimrichHannotJobBundle::class)->setLoadAfter([
                '\HeimrichHannot\ListBundle\HeimrichHannotContaoListBundle',
                '\HeimrichHannot\ReaderBundle\HeimrichHannotContaoReaderBundle',
                ContaoCoreBundle::class,
            ]),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load('@HeimrichHannotJobBundle/Resources/config/services.yml');
    }

    public function getExtensionConfig($extensionName, array $extensionConfigs, ContainerBuilder $container)
    {
        if (class_exists('\HeimrichHannot\ListBundle\HeimrichHannotContaoListBundle')) {
            $extensionConfigs = ContainerUtil::mergeConfigFile(
                'huh_list',
                $extensionName,
                $extensionConfigs,
                __DIR__.'/../Resources/config/config_list.yml'
            );
        }

        if (class_exists('\HeimrichHannot\ReaderBundle\HeimrichHannotContaoReaderBundle')) {
            $extensionConfigs = ContainerUtil::mergeConfigFile(
                'huh_reader',
                $extensionName,
                $extensionConfigs,
                __DIR__.'/../Resources/config/config_reader.yml'
            );
        }

        return $extensionConfigs;
    }
}
