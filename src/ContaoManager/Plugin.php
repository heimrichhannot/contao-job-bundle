<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\JobBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ContainerBuilder;
use Contao\ManagerPlugin\Config\ExtensionPluginInterface;
use HeimrichHannot\JobBundle\HeimrichHannotContaoJobBundle;
use HeimrichHannot\UtilsBundle\Container\ContainerUtil;

class Plugin implements BundlePluginInterface, ExtensionPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        $bundles = [];

        if (class_exists('\HeimrichHannot\ListBundle\HeimrichHannotContaoListBundle')) {
            $bundles[] = \HeimrichHannot\ListBundle\HeimrichHannotContaoListBundle::class;
        }

        if (class_exists('\HeimrichHannot\ReaderBundle\HeimrichHannotContaoReaderBundle')) {
            $bundles[] = \HeimrichHannot\ReaderBundle\HeimrichHannotContaoReaderBundle::class;
        }

        $bundles[] = ContaoCoreBundle::class;

        return [
            BundleConfig::create(HeimrichHannotContaoJobBundle::class)->setLoadAfter($bundles),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionConfig($extensionName, array $extensionConfigs, ContainerBuilder $container)
    {
        if (class_exists('\HeimrichHannot\ListBundle\HeimrichHannotContaoListBundle')) {
            $extensionConfigs = ContainerUtil::mergeConfigFile(
                'huh_list',
                $extensionName,
                $extensionConfigs,
                __DIR__ . '/../Resources/config/config_list.yml'
            );
        }

        if (class_exists('\HeimrichHannot\ReaderBundle\HeimrichHannotContaoReaderBundle')) {
            $extensionConfigs = ContainerUtil::mergeConfigFile(
                'huh_reader',
                $extensionName,
                $extensionConfigs,
                __DIR__ . '/../Resources/config/config_reader.yml'
            );
        }

        return $extensionConfigs;
    }
}
